package controllers;

import entities.User;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import services.UserService;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.AnchorPane;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.nio.file.StandardCopyOption;
import java.sql.SQLException;
import java.util.HashSet;
import java.util.Set;
import java.util.UUID;

public class Register {

    @FXML private TextField emailField;
    @FXML private ComboBox<String> roleComboBox;
    @FXML private TextField nomField;
    @FXML private TextField prenomField;
    @FXML private PasswordField passwordField;
    @FXML private TextField numTelField;
    @FXML private ComboBox<String> genreComboBox;
    @FXML private TextField imageField;
    @FXML private Button uploadButton;
    @FXML private ImageView imagePreview;
    @FXML private Button registerButton;
    @FXML private Button cancelButton;
    @FXML private Label statusLabel;
    @FXML private AnchorPane root;

    private UserService userService;
    private File selectedImageFile;
    private String imagePath = "";

    public Register() {
        this.userService = new UserService();
    }

    @FXML
    public void initialize() {
        // Initialisation des ComboBox
        roleComboBox.setItems(FXCollections.observableArrayList("ROLE_USER", "ROLE_DOCTEUR","ROLE_CHAUFFEUR"));
        genreComboBox.setItems(FXCollections.observableArrayList("Homme", "Femme", "Autre"));

        // Masquer la preview initialement
        imagePreview.setVisible(false);
    }

    @FXML
    private void handleImageUpload() {
        FileChooser fileChooser = new FileChooser();
        fileChooser.setTitle("Choisir une image de profil");
        fileChooser.getExtensionFilters().addAll(
                new FileChooser.ExtensionFilter("Images", "*.png", "*.jpg", "*.jpeg")
        );

        File file = fileChooser.showOpenDialog(emailField.getScene().getWindow());
        if (file != null) {
            selectedImageFile = file;
            imageField.setText(file.getName());

            // Afficher la preview
            Image image = new Image(file.toURI().toString());
            imagePreview.setImage(image);
            imagePreview.setVisible(true);
        }
    }

    @FXML
    private void handleRegister() {
        try {
            // Validation des champs
            if (!validateFields()) {
                return;
            }

            // Création de l'utilisateur
            User user = new User();
            user.setEmail(emailField.getText());
            user.setNom(nomField.getText());
            user.setPrenom(prenomField.getText());
            user.setPassword(passwordField.getText());
            user.setNumTel(numTelField.getText());
            user.setGenre(genreComboBox.getValue());
            user.setResetToken(UUID.randomUUID().toString());

            // Gestion de l'image si sélectionnée
            if (selectedImageFile != null) {
                String photoPath = saveProfilePhoto(selectedImageFile, UUID.randomUUID().toString());
                user.setImage(photoPath);
            }

            // Définition des rôles
            Set<String> roles = new HashSet<>();
            roles.add(roleComboBox.getValue());
            user.setRoles(roles);

            // Ajout à la base de données
            userService.ajouter(user);

            // Message de succès
            showAlert("Succès", "Inscription réussie",
                    "L'utilisateur a été enregistré avec succès!", Alert.AlertType.INFORMATION);

            // Réinitialisation du formulaire
            clearFields();

        } catch (SQLException e) {
            showAlert("Erreur", "Erreur de base de données", e.getMessage(), Alert.AlertType.ERROR);
            e.printStackTrace();
        } catch (IOException e) {
            showAlert("Erreur", "Erreur de fichier", e.getMessage(), Alert.AlertType.ERROR);
            e.printStackTrace();
        }
    }

    private String saveProfilePhoto(File photoFile, String userId) throws IOException {
        // Chemin vers le dossier de stockage
        String targetDir = "src/main/resources/uploads";
        File directory = new File(targetDir);
        if (!directory.exists()) {
            directory.mkdirs();
        }

        // Création d'un nom de fichier unique
        String extension = getFileExtension(photoFile.getName());
        String newFileName = userId + "_profile" + extension;
        Path targetPath = Paths.get(targetDir, newFileName);

        // Copie du fichier
        Files.copy(photoFile.toPath(), targetPath, StandardCopyOption.REPLACE_EXISTING);

        // Retour du chemin relatif
        return "/uploads/" + newFileName;
    }

    private String getFileExtension(String fileName) {
        int dotIndex = fileName.lastIndexOf('.');
        return (dotIndex == -1) ? "" : fileName.substring(dotIndex);
    }

    private boolean validateFields() {
        if (emailField.getText().isEmpty() || nomField.getText().isEmpty() ||
                prenomField.getText().isEmpty() || passwordField.getText().isEmpty() ||
                numTelField.getText().isEmpty() || roleComboBox.getValue() == null ||
                genreComboBox.getValue() == null) {

            statusLabel.setText("Tous les champs sont obligatoires!");
            statusLabel.setStyle("-fx-text-fill: #f44336;");
            return false;
        }

        // Validation email
        if (!emailField.getText().contains("@")) {
            statusLabel.setText("Veuillez entrer un email valide");
            statusLabel.setStyle("-fx-text-fill: #f44336;");
            return false;
        }

        return true;
    }

    private void clearFields() {
        emailField.clear();
        nomField.clear();
        prenomField.clear();
        passwordField.clear();
        numTelField.clear();
        roleComboBox.getSelectionModel().clearSelection();
        genreComboBox.getSelectionModel().clearSelection();
        imageField.clear();
        imagePreview.setImage(null);
        imagePreview.setVisible(false);
        selectedImageFile = null;
        statusLabel.setText("");
    }

    private void showAlert(String title, String header, String content, Alert.AlertType type) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }

    @FXML
    private void handleCancel() {
       try {
           FXMLLoader loader = new FXMLLoader(getClass().getResource("/Login.fxml"));
           Parent root = loader.load();

           // Obtenir la Stage actuelle à partir du HBox
           Stage stage = (Stage) cancelButton.getScene().getWindow();

           // Changer la scène
           Scene scene = new Scene(root);
           stage.setScene(scene);
           stage.centerOnScreen();
           stage.show();
       }catch (IOException e) {
           e.printStackTrace();
       }
    }
}