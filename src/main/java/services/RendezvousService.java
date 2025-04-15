package services;

import entities.Rendezvous;
import entities.User;
import utils.MyDatabase;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

public class RendezvousService implements IService<Rendezvous> {

    private Connection cnx;
    private UserService userService;

    public RendezvousService() {
        cnx = MyDatabase    .getInstance().getConnection();
        userService = new UserService();
    }

    @Override
    public void ajouter(Rendezvous rendezvous) throws SQLException {
        String req = "INSERT INTO rendezvous (date, adresse, patient_id, medecin_id, domicile, realise) " +
                "VALUES (?, ?, ?, ?, ?, ?)";
        PreparedStatement ps = cnx.prepareStatement(req);

        ps.setObject(1, rendezvous.getDate());
        ps.setString(2, rendezvous.getAdresse());
        ps.setInt(3, rendezvous.getPatient().getId());
        ps.setInt(4, rendezvous.getMedecin().getId());
        ps.setBoolean(5, rendezvous.isDomicile());
        ps.setBoolean(6, rendezvous.getRealise() != null ? rendezvous.getRealise() : false);

        ps.executeUpdate();
        System.out.println("Rendez-vous ajouté avec succès !");
    }

    @Override
    public void modifier(Rendezvous rendezvous) throws SQLException {
        String req = "UPDATE rendezvous SET date=?, adresse=?, patient_id=?, medecin_id=?, " +
                "domicile=?, realise=? WHERE id=?";
        PreparedStatement ps = cnx.prepareStatement(req);

        ps.setObject(1, rendezvous.getDate());
        ps.setString(2, rendezvous.getAdresse());
        ps.setInt(3, rendezvous.getPatient().getId());
        ps.setInt(4, rendezvous.getMedecin().getId());
        ps.setBoolean(5, rendezvous.isDomicile());
        ps.setBoolean(6, rendezvous.getRealise() != null ? rendezvous.getRealise() : false);
        ps.setInt(7, rendezvous.getId());

        ps.executeUpdate();
        System.out.println("Rendez-vous modifié avec succès !");
    }

    @Override
    public void supprimer(int id) throws SQLException {
        String req = "DELETE FROM rendezvous WHERE id=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setInt(1, id);
        ps.executeUpdate();
        System.out.println("Rendez-vous supprimé avec succès !");
    }

    @Override
    public List<Rendezvous> afficher() throws SQLException {
        List<Rendezvous> rendezvousList = new ArrayList<>();

        String req = "SELECT * FROM rendezvous";
        PreparedStatement ps = cnx.prepareStatement(req);
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            Rendezvous rendezvous = new Rendezvous();
            rendezvous.setId(rs.getInt("id"));
            rendezvous.setDate(rs.getObject("date", LocalDateTime.class));
            rendezvous.setAdresse(rs.getString("adresse"));

            // Récupérer les objets User associés
            int patientId = rs.getInt("patient_id");
            int medecinId = rs.getInt("medecin_id");
            User patient = userService.findById(patientId); // Vous devez implémenter cette méthode dans UserService
            User medecin = userService.findById(medecinId);

            rendezvous.setPatient(patient);
            rendezvous.setMedecin(medecin);
            rendezvous.setDomicile(rs.getBoolean("domicile"));
            rendezvous.setRealise(rs.getBoolean("realise"));

            rendezvousList.add(rendezvous);
        }

        return rendezvousList;
    }

    // Méthodes supplémentaires utiles
    public List<Rendezvous> findByPatientId(int patientId) throws SQLException {
        List<Rendezvous> rendezvousList = new ArrayList<>();

        String req = "SELECT * FROM rendezvous WHERE patient_id=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setInt(1, patientId);
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            Rendezvous rendezvous = mapResultSetToRendezvous(rs);
            rendezvousList.add(rendezvous);
        }

        return rendezvousList;
    }

    public List<Rendezvous> findByMedecinId(int medecinId) throws SQLException {
        List<Rendezvous> rendezvousList = new ArrayList<>();

        String req = "SELECT * FROM rendezvous WHERE medecin_id=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setInt(1, medecinId);
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            Rendezvous rendezvous = mapResultSetToRendezvous(rs);
            rendezvousList.add(rendezvous);
        }

        return rendezvousList;
    }

    public List<Rendezvous> findByDateRange(LocalDateTime start, LocalDateTime end) throws SQLException {
        List<Rendezvous> rendezvousList = new ArrayList<>();

        String req = "SELECT * FROM rendezvous WHERE date BETWEEN ? AND ?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setObject(1, start);
        ps.setObject(2, end);
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            Rendezvous rendezvous = mapResultSetToRendezvous(rs);
            rendezvousList.add(rendezvous);
        }

        return rendezvousList;
    }

    private Rendezvous mapResultSetToRendezvous(ResultSet rs) throws SQLException {
        Rendezvous rendezvous = new Rendezvous();
        rendezvous.setId(rs.getInt("id"));
        rendezvous.setDate(rs.getObject("date", LocalDateTime.class));
        rendezvous.setAdresse(rs.getString("adresse"));

        int patientId = rs.getInt("patient_id");
        int medecinId = rs.getInt("medecin_id");
        User patient = userService.findById(patientId);
        User medecin = userService.findById(medecinId);

        rendezvous.setPatient(patient);
        rendezvous.setMedecin(medecin);
        rendezvous.setDomicile(rs.getBoolean("domicile"));
        rendezvous.setRealise(rs.getBoolean("realise"));

        return rendezvous;
    }
}