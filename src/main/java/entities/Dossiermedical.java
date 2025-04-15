package entities;

public class Dossiermedical {
    private Integer id;
    private String historique;
    private User patient;

    // Constructors
    public Dossiermedical() {
    }

    public Dossiermedical(User patient) {
        this.patient = patient;
    }

    public Dossiermedical(String historique, User patient) {
        this.historique = historique;
        this.patient = patient;
    }

    // Getters and Setters
    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getHistorique() {
        return historique;
    }

    public void setHistorique(String historique) {
        this.historique = historique;
    }

    public User getPatient() {
        return patient;
    }

    public void setPatient(User patient) {
        this.patient = patient;
    }

    // Utility methods
    public boolean isValid() {
        return patient != null;
    }

    public void addToHistorique(String newEntry) {
        if (historique == null || historique.isEmpty()) {
            historique = newEntry;
        } else {
            historique += "\n" + newEntry;
        }
    }

    @Override
    public String toString() {
        return "DossierMedical{" +
                "id=" + id +
                ", patient=" + (patient != null ? patient.getFullName() : "null") +
                ", historiqueLength=" + (historique != null ? historique.length() : 0) +
                '}';
    }
}