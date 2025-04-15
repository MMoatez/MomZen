package services;

import entities.Consultation;
import entities.Dossiermedical;
import entities.User;
import utils.MyDatabase;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ConsultationService implements IService<Consultation> {
    private Connection connection;

    public ConsultationService() {
        this.connection = MyDatabase.getInstance().getConnection();
    }

    @Override
    public void ajouter(Consultation consultation) throws SQLException {
        String sql = "INSERT INTO consultation (ordonnance, dossier_id, medecin_id) VALUES (?, ?, ?)";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, consultation.getOrdonnance());
            preparedStatement.setInt(2, consultation.getDossier().getId());
            preparedStatement.setInt(3, consultation.getMedecin().getId());
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public void modifier(Consultation consultation) throws SQLException {
        String sql = "UPDATE consultation SET ordonnance = ?, dossier_id = ?, medecin_id = ? WHERE id = ?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, consultation.getOrdonnance());
            preparedStatement.setInt(2, consultation.getDossier().getId());
            preparedStatement.setInt(3, consultation.getMedecin().getId());
            preparedStatement.setInt(4, consultation.getId());
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public void supprimer(int id) throws SQLException {
        String sql = "DELETE FROM consultation WHERE id = ?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setInt(1, id);
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public List<Consultation> afficher() throws SQLException {
        List<Consultation> consultations = new ArrayList<>();
        String sql = "SELECT * FROM consultation";

        try (Statement statement = connection.createStatement();
             ResultSet resultSet = statement.executeQuery(sql)) {

            while (resultSet.next()) {
                Consultation consultation = new Consultation();
                consultation.setId(resultSet.getInt("id"));
                consultation.setOrdonnance(resultSet.getString("ordonnance"));
                // Note: Dossiermedical and User objects need proper initialization
                // This is a simplified version
                consultations.add(consultation);
            }
        }
        return consultations;
    }
}