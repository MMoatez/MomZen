package controllers;

import entities.User;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.stage.Stage;
import services.IService;
import services.UserService;
import utils.SessionManager;

import java.io.IOException;
import java.sql.SQLException;

public class DashboardAdmin {
    @FXML
    private Button logoutButton;
    @FXML
    private TableView<User> userTable;

    private IService<User> userService = new UserService();

    @FXML
    public void initialize() {
        try {
            // Configurez la colonne Actions
            TableColumn<User, Void> actionColumn = (TableColumn<User, Void>) userTable.getColumns().get(5);
            actionColumn.setCellFactory(param -> new TableCell<>() {
                private final Button deleteButton = new Button("Supprimer");
                private final HBox buttonsContainer = new HBox(5); // Spacing between buttons

                {
                    deleteButton.setStyle("-fx-background-color: #e74c3c; -fx-text-fill: white; -fx-background-radius: 5;");

                    // Delete button action
                    deleteButton.setOnAction(event -> {
                        User user = getTableRow().getItem();
                        if (user != null) {
                            // Show confirmation dialog
                            Alert confirmation = new Alert(Alert.AlertType.CONFIRMATION);
                            confirmation.setTitle("Confirmation de suppression");
                            confirmation.setHeaderText("Voulez-vous vraiment supprimer cet utilisateur ?");
                            confirmation.setContentText("Cette action est irréversible.");
                            confirmation.showAndWait().ifPresent(response -> {
                                if (response == ButtonType.OK) {
                                    try {
                                        userService.supprimer(user.getId());
                                        // Reload the users
                                        loadUsers();
                                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Utilisateur supprimé", "L'utilisateur a été supprimé avec succès.");
                                    } catch (SQLException e) {
                                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la suppression", e.getMessage());
                                        e.printStackTrace();
                                    }
                                }
                            });
                        }
                    });

                    buttonsContainer.getChildren().addAll(deleteButton);
                }

                @Override
                protected void updateItem(Void item, boolean empty) {
                    super.updateItem(item, empty);
                    if (empty) {
                        setGraphic(null);
                    } else {
                        setGraphic(buttonsContainer);
                    }
                }
            });

            loadUsers();
        } catch (SQLException e) {
            e.printStackTrace();
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors du chargement", e.getMessage());
        }
    }

    private void loadUsers() throws SQLException {
        ObservableList<User> users = FXCollections.observableArrayList(userService.afficher());
        userTable.setItems(users);
    }

    private void showAlert(Alert.AlertType type, String title, String header, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(header);
        alert.setContentText(content);
        alert.showAndWait();
    }
    @FXML
    private void handleLogout() {
        try {
            SessionManager.getInstance().logout();
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/Login.fxml"));
            Parent root = loader.load();
            Stage stage = (Stage) logoutButton.getScene().getWindow();
            stage.setScene(new Scene(root));
            stage.setTitle("Connexion");
            stage.centerOnScreen();
            showAlert(Alert.AlertType.INFORMATION, "Déconnexion réussie", "Vous avez été déconnecté avec succès.", "");
        } catch (IOException e) {
            showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la déconnexion", e.getMessage());
            e.printStackTrace();
        }
    }

}