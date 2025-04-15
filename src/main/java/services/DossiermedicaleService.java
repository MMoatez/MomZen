package services;

import entities.Dossiermedical;
import utils.MyDatabase;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class DossiermedicaleService implements IService<Dossiermedical> {
    private Connection connection;

    public DossiermedicaleService() {
        this.connection = MyDatabase.getInstance().getConnection();
    }

    @Override
    public void ajouter(Dossiermedical dossiermedical) throws SQLException {
        String sql = "INSERT INTO dossiermedical (historique, patient_id) VALUES (?, ?)";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, dossiermedical.getHistorique());
            preparedStatement.setInt(2, dossiermedical.getPatient().getId());
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public void modifier(Dossiermedical dossiermedical) throws SQLException {
        String sql = "UPDATE dossiermedical SET historique = ?, patient_id = ? WHERE id = ?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setString(1, dossiermedical.getHistorique());
            preparedStatement.setInt(2, dossiermedical.getPatient().getId());
            preparedStatement.setInt(3, dossiermedical.getId());
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public void supprimer(int id) throws SQLException {
        String sql = "DELETE FROM dossiermedical WHERE id = ?";
        try (PreparedStatement preparedStatement = connection.prepareStatement(sql)) {
            preparedStatement.setInt(1, id);
            preparedStatement.executeUpdate();
        }
    }

    @Override
    public List<Dossiermedical> afficher() throws SQLException {
        List<Dossiermedical> dossiers = new ArrayList<>();
        String sql = "SELECT * FROM dossiermedical";

        try (Statement statement = connection.createStatement();
             ResultSet resultSet = statement.executeQuery(sql)) {

            while (resultSet.next()) {
                Dossiermedical dossier = new Dossiermedical();
                dossier.setId(resultSet.getInt("id"));
                dossier.setHistorique(resultSet.getString("historique"));
                // Note: You might need to fetch User object properly based on patient_id
                // This is a simplified version
                dossiers.add(dossier);
            }
        }
        return dossiers;
    }
}