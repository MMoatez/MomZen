package entities;

import java.util.HashSet;
import java.util.Set;

public class User {
    private Integer id;
    private String email;
    private Set<String> roles = new HashSet<>();
    private String nom;
    private String prenom;
    private String password;
    private String numTel;
    private String genre;
    private String image;
    private String resetToken;

    // Constructors
    public User() {
    }

    public User(String email, String nom, String prenom, String password, String numTel, String genre) {
        this.email = email;
        this.nom = nom;
        this.prenom = prenom;
        this.password = password;
        this.numTel = numTel;
        this.genre = genre;
    }

    // Getters and Setters
    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public Set<String> getRoles() {
        return new HashSet<>(roles); // Return a copy to preserve encapsulation
    }

    public void setRoles(Set<String> roles) {
        this.roles = new HashSet<>(roles);
    }

    public void addRole(String role) {
        this.roles.add(role);
    }

    public void removeRole(String role) {
        this.roles.remove(role);
    }

    public boolean hasRole(String role) {
        return this.roles.contains(role);
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getNumTel() {
        return numTel;
    }

    public void setNumTel(String numTel) {
        this.numTel = numTel;
    }

    public String getGenre() {
        return genre;
    }

    public void setGenre(String genre) {
        this.genre = genre;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public String getResetToken() {
        return resetToken;
    }

    public void setResetToken(String resetToken) {
        this.resetToken = resetToken;
    }

    // Utility methods
    public String getFullName() {
        return prenom + " " + nom;
    }

    public String getUserIdentifier() {
        return email;
    }

    public void eraseCredentials() {
        this.password = null;
    }

    @Override
    public String toString() {
        return "User{" +
                "id=" + id +
                ", email='" + email + '\'' +
                ", roles=" + roles +
                ", nom='" + nom + '\'' +
                ", prenom='" + prenom + '\'' +
                ", numTel='" + numTel + '\'' +
                ", genre='" + genre + '\'' +
                '}';
    }

    // Validation methods (similar to the annotations you had)
    public boolean validateEmail() {
        return email != null && email.contains("@") && email.contains(".");
    }

    public boolean validatePassword() {
        return password != null &&
                password.length() >= 6 &&
                password.matches(".*[A-Z].*") &&
                password.matches(".*[a-z].*") &&
                password.matches(".*[\\W].*");
    }

    public boolean validatePhone() {
        return numTel != null && numTel.matches("^\\d{8}$");
    }

    public boolean validateRequiredFields() {
        return email != null && !email.isEmpty() &&
                nom != null && !nom.isEmpty() &&
                prenom != null && !prenom.isEmpty() &&
                password != null && !password.isEmpty() &&
                numTel != null && !numTel.isEmpty() &&
                genre != null && !genre.isEmpty();
    }
}