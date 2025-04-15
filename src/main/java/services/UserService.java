    package services;

    import java.sql.Connection;
    import java.sql.PreparedStatement;
    import java.sql.ResultSet;
    import java.sql.SQLException;
    import java.util.*;

    import entities.User;
    import org.mindrot.jbcrypt.BCrypt;
    import utils.MyDatabase;

    public class UserService implements IService<User> {

        private Connection cnx;

        public UserService() {
            cnx = MyDatabase.getInstance().getConnection();
        }

        @Override
        public void ajouter(User user) throws SQLException {
            String req = "INSERT INTO user (email, roles, nom, prenom, password, num_tel, genre, image, reset_token) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            PreparedStatement ps = cnx.prepareStatement(req);

            // Ne pas hacher le mot de passe
            String plainPassword = user.getPassword();

            ps.setString(1, user.getEmail());
            ps.setString(2, convertRolesToJson(new ArrayList<>(user.getRoles())));
            ps.setString(3, user.getNom());
            ps.setString(4, user.getPrenom());
            ps.setString(5, plainPassword); // Stocker le mot de passe en clair
            ps.setString(6, user.getNumTel());
            ps.setString(7, user.getGenre());
            ps.setString(8, user.getImage());
            ps.setString(9, user.getResetToken());

            ps.executeUpdate();
            System.out.println("User ajouté avec succès !");
        }

        @Override
        public void modifier(User user) throws SQLException {
            String req = "UPDATE user SET email=?, roles=?, nom=?, prenom=?, password=?, " +
                    "num_tel=?, genre=?, image=?, reset_token=? WHERE id=?";
            PreparedStatement ps = cnx.prepareStatement(req);

            // Ne pas hacher le mot de passe
            String passwordToStore = user.getPassword();
            if (passwordToStore == null || passwordToStore.isEmpty()) {
                // Si le mot de passe n'est pas fourni, garder l'ancien
                passwordToStore = getCurrentPassword(user.getId());
            }

            ps.setString(1, user.getEmail());
            ps.setString(2, convertRolesToJson(new ArrayList<>(user.getRoles())));
            ps.setString(3, user.getNom());
            ps.setString(4, user.getPrenom());
            ps.setString(5, passwordToStore); // Stocker le mot de passe en clair
            ps.setString(6, user.getNumTel());
            ps.setString(7, user.getGenre());
            ps.setString(8, user.getImage());
            ps.setString(9, user.getResetToken());
            ps.setInt(10, user.getId());

            ps.executeUpdate();
            System.out.println("User modifié avec succès !");
        }
        private String getCurrentPassword(int userId) throws SQLException {
            String query = "SELECT password FROM user WHERE id = ?";
            try (PreparedStatement stmt = cnx.prepareStatement(query)) {
                stmt.setInt(1, userId);
                ResultSet rs = stmt.executeQuery();
                if (rs.next()) {
                    return rs.getString("password");
                }
            }
            return null;
        }

        public User authenticate(String email, String password) throws SQLException {
            String query = "SELECT * FROM user WHERE email = ?";

            try (PreparedStatement statement = cnx.prepareStatement(query)) {
                statement.setString(1, email);
                ResultSet resultSet = statement.executeQuery();

                if (resultSet.next()) {
                    String storedPassword = resultSet.getString("password");

                    // Comparaison directe des mots de passe sans cryptage
                    if (password.equals(storedPassword)) {
                        return mapResultSetToUser(resultSet);
                    }
                }
            }
            return null;
        }
        // Other existing methods remain the same (supprimer, afficher, findByEmail, findById, etc.)

        private String convertRolesToJson(List<String> roles) {
            try {
                // Simple JSON conversion without external library
                if (roles == null || roles.isEmpty()) {
                    return "[]";
                }
                StringBuilder sb = new StringBuilder("[");
                for (int i = 0; i < roles.size(); i++) {
                    if (i > 0) sb.append(",");
                    sb.append("\"").append(roles.get(i)).append("\"");
                }
                sb.append("]");
                return sb.toString();
            } catch (Exception e) {
                return "[\"ROLE_USER\"]"; // Default value if error
            }
        }

        private User mapResultSetToUser(ResultSet rs) throws SQLException {
            User user = new User();
            user.setId(rs.getInt("id"));
            user.setEmail(rs.getString("email"));

            // Handle roles from JSON format
            String rolesJson = rs.getString("roles");
            Set<String> roles = new HashSet<>();
            if (rolesJson != null && !rolesJson.isEmpty()) {
                // Simple JSON parsing without external library
                String cleaned = rolesJson.replaceAll("[\\[\\]\"]", "");
                String[] roleArray = cleaned.split(",");
                for (String role : roleArray) {
                    if (!role.trim().isEmpty()) {
                        roles.add(role.trim());
                    }
                }
            }
            user.setRoles(roles);

            user.setNom(rs.getString("nom"));
            user.setPrenom(rs.getString("prenom"));
            user.setPassword(rs.getString("password"));
            user.setNumTel(rs.getString("num_tel"));
            user.setGenre(rs.getString("genre"));
            user.setImage(rs.getString("image"));
            user.setResetToken(rs.getString("reset_token"));

            return user;
        }

    @Override
    public void supprimer(int id) throws SQLException {
        String req = "DELETE FROM user WHERE id=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setInt(1, id);
        ps.executeUpdate();
        System.out.println("User supprimé avec succès !");
    }

    @Override
    public List<User> afficher() throws SQLException {
        List<User> users = new ArrayList<>();

        String req = "SELECT * FROM user";
        PreparedStatement ps = cnx.prepareStatement(req);
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            User user = mapResultSetToUser(rs);
            users.add(user);
        }

        return users;
    }

    public User findByEmail(String email) throws SQLException {
        String req = "SELECT * FROM user WHERE email=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setString(1, email);
        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            return mapResultSetToUser(rs);
        }

        return null;
    }

    public User findById(int id) throws SQLException {
        String req = "SELECT * FROM user WHERE id=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setInt(1, id);
        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            return mapResultSetToUser(rs);
        }

        return null;
    }


    // Méthodes supplémentaires utiles
    public List<User> findByRole(String role) throws SQLException {
        List<User> users = new ArrayList<>();
        String req = "SELECT * FROM user WHERE roles LIKE ?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setString(1, "%" + role + "%");
        ResultSet rs = ps.executeQuery();

        while (rs.next()) {
            users.add(mapResultSetToUser(rs));
        }

        return users;
    }

    public boolean emailExists(String email) throws SQLException {
        String req = "SELECT COUNT(*) FROM user WHERE email=?";
        PreparedStatement ps = cnx.prepareStatement(req);
        ps.setString(1, email);
        ResultSet rs = ps.executeQuery();

        if (rs.next()) {
            return rs.getInt(1) > 0;
        }

        return false;
    }


}