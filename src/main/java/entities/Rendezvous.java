package entities;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class Rendezvous {
    private Integer id;
    private LocalDateTime date;
    private String adresse;
    private User patient;
    private User medecin;
    private boolean domicile = false;
    private Boolean realise;

    // Constructors
    public Rendezvous() {
        this.date = LocalDateTime.now();
    }

    public Rendezvous(LocalDateTime date, User patient, User medecin) {
        this.date = date;
        this.patient = patient;
        this.medecin = medecin;
    }

    // Getters and Setters
    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public LocalDateTime getDate() {
        return date;
    }

    public void setDate(LocalDateTime date) {
        this.date = date;
    }

    public String getAdresse() {
        return adresse;
    }

    public void setAdresse(String adresse) {
        this.adresse = adresse;
    }

    public User getPatient() {
        return patient;
    }

    public void setPatient(User patient) {
        this.patient = patient;
    }

    public User getMedecin() {
        return medecin;
    }

    public void setMedecin(User medecin) {
        this.medecin = medecin;
    }

    public boolean isDomicile() {
        return domicile;
    }

    public void setDomicile(boolean domicile) {
        this.domicile = domicile;
    }

    public Boolean isRealise() {
        return realise;
    }

    public Boolean getRealise() {
        return realise;
    }

    public void setRealise(Boolean realise) {
        this.realise = realise;
    }

    // Utility methods
    public boolean isPast() {
        return date.isBefore(LocalDateTime.now());
    }

    public boolean isUpcoming() {
        return date.isAfter(LocalDateTime.now());
    }

    public boolean isValid() {
        return date != null && patient != null && medecin != null;
    }

    @Override
    public String toString() {
        return "Rendezvous{" +
                "id=" + id +
                ", date=" + date +
                ", patient=" + (patient != null ? patient.getNom() + " " + patient.getPrenom() : "null") +
                ", medecin=" + (medecin != null ? medecin.getNom() + " " + medecin.getPrenom() : "null") +
                ", domicile=" + domicile +
                ", realise=" + realise +
                '}';
    }

    // Additional convenience methods
    public String getFormattedDate() {
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy HH:mm");
        return date.format(formatter);
    }

    public String getLocation() {
        return domicile ? "À domicile: " + adresse : "Cabinet médical";
    }

    public String getStatus() {
        if (realise == null) return "Non défini";
        return realise ? "Réalisé" : "Non réalisé";
    }
}