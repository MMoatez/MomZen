package org.example;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;

import java.io.IOException;

public class Main extends Application {

    @Override
    public void start(Stage primaryStage) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/login.fxml"));
            Scene scene = new Scene(loader.load(), 800, 600); // Match FXML dimensions
            primaryStage.setTitle("User Login");
            primaryStage.setScene(scene);
            primaryStage.show();
        } catch (IOException e) {
            e.printStackTrace();
            System.err.println("Error loading Register.fxml: " + e.getMessage());
        }
    }

    public static void main(String[] args) {
        launch(args); // Launch the JavaFX application
    }
}