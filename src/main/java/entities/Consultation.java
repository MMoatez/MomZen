package entities;

public class Consultation {
    private Integer id;
    private String ordonnance;
    private Dossiermedical dossier;
    private User medecin;

    // Constructors
    public Consultation() {
    }

    public Consultation(Dossiermedical dossier, User medecin) {
        this.dossier = dossier;
        this.medecin = medecin;
    }

    public Consultation(String ordonnance, Dossiermedical dossier, User medecin) {
        this.ordonnance = ordonnance;
        this.dossier = dossier;
        this.medecin = medecin;
    }

    // Getters and Setters
    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getOrdonnance() {
        return ordonnance;
    }

    public void setOrdonnance(String ordonnance) {
        this.ordonnance = ordonnance;
    }

    public Dossiermedical getDossier() {
        return dossier;
    }

    public void setDossier(Dossiermedical dossier) {
        this.dossier = dossier;
    }

    public User getMedecin() {
        return medecin;
    }

    public void setMedecin(User medecin) {
        this.medecin = medecin;
    }

    // Utility methods
    public boolean isValid() {
        return dossier != null && medecin != null;
    }

    public void appendOrdonnance(String newPrescription) {
        if (ordonnance == null || ordonnance.isEmpty()) {
            ordonnance = newPrescription;
        } else {
            ordonnance += "\n\n" + newPrescription;
        }
    }

    @Override
    public String toString() {
        return "Consultation{" +
                "id=" + id +
                ", medecin=" + (medecin != null ? medecin.getFullName() : "null") +
                ", patient=" + (dossier != null && dossier.getPatient() != null ?
                dossier.getPatient().getFullName() : "null") +
                ", ordonnanceLength=" + (ordonnance != null ? ordonnance.length() : 0) +
                '}';
    }
}