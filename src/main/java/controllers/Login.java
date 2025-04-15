package controllers;

import entities.User;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.stage.Stage;
import services.UserService;
import utils.SessionManager;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class Login {


    @FXML
    private TextField emailField;

    @FXML
    private PasswordField passwordField;

    @FXML
    private Label errorLabel;

    private final UserService serviceUser = new UserService();

    @FXML
    private HBox mainContainer;  // Référence vers le HBox avec fx:id="mainContainer"

    @FXML
    private void loadRegistration() {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/Register.fxml"));
            Parent root = loader.load();

            Stage stage = (Stage) mainContainer.getScene().getWindow();

            Scene scene = new Scene(root);
            stage.setScene(scene);
            stage.centerOnScreen();
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
            Alert alert = new Alert(Alert.AlertType.ERROR, "Erreur lors du chargement de la page d'inscription !");
            alert.show();
        }
    }

    @FXML
    private void handleLogin() {
        String email = emailField.getText();
        String password = passwordField.getText();

        if (email.isEmpty() || password.isEmpty()) {
            errorLabel.setText("Email et mot de passe sont requis");
            return;
        }

        try {
            User user = serviceUser.authenticate(email, password);

            if (user != null) {
                SessionManager.getInstance().setCurrentUser(user);

                redirectBasedOnRole(new ArrayList<>(user.getRoles()));
            } else {
                errorLabel.setText("Email ou mot de passe incorrect");
            }
        } catch (SQLException e) {
            errorLabel.setText("Erreur de connexion à la base de données");
            e.printStackTrace();
        } catch (IOException e) {
            errorLabel.setText("Erreur de chargement de l'interface");
            e.printStackTrace();
        }
    }


    private void redirectBasedOnRole(List<String> roles) throws IOException {
        String fxmlFile;
        String title;

        if (roles.contains("ROLE_ADMIN")) {
            fxmlFile = "/DashboardPatient.fxml";
            title = "Tableau de bord Admin";
        } else if (roles.contains("ROLE_USER")) {
            fxmlFile = "/DashboardAdmin.fxml";
            title = "Tableau de bord Patient";
        } else if (roles.contains("ROLE_DOCTEUR")) {
            fxmlFile = "/DashboardMedcin.fxml";
            title = "Tableau de bord Medcin";
        }
        else if (roles.contains("ROLE_CHAUFFEUR")) {
            fxmlFile = "/DashboardChauffeur.fxml";
            title = "Tableau de bord Chauffeur";
        }
        else if (roles.contains("ROLE_CHAUFFEUR")) {
            fxmlFile = "/DashboardChauffeur.fxml";
            title = "Tableau de bord Chauffeur";
        }

        else {
            // Rôle par défaut si aucun rôle reconnu
            fxmlFile = "/UserDashboard.fxml";
            title = "Tableau de bord Utilisateur";
        }

        // Charger la nouvelle interface
        FXMLLoader loader = new FXMLLoader(getClass().getResource(fxmlFile));
        Parent root = loader.load();

        // Configurer la nouvelle scène
        Stage stage = (Stage) emailField.getScene().getWindow();
        stage.setScene(new Scene(root));
        stage.setTitle(title);
        stage.centerOnScreen();

    }

}
