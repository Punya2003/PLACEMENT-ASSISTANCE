document.addEventListener("DOMContentLoaded", function() {
    const personalInfo = document.getElementById("personalInfo");
    const demographics = document.getElementById("demographics");
    const academicInfo = document.getElementById("academicInfo");
    const placementPreferences = document.getElementById("placementPreferences");

    document.getElementById("toDemographics").addEventListener("click", function() {
        personalInfo.classList.remove("active");
        demographics.classList.add("active");
    });

    document.getElementById("toPersonalInfo").addEventListener("click", function() {
        demographics.classList.remove("active");
        personalInfo.classList.add("active");
    });

    document.getElementById("toAcademicInfo").addEventListener("click", function() {
        demographics.classList.remove("active");
        academicInfo.classList.add("active");
    });

    document.getElementById("toDemographicsBack").addEventListener("click", function() {
        academicInfo.classList.remove("active");
        demographics.classList.add("active");
    });

    document.getElementById("toPlacementPreferences").addEventListener("click", function() {
        academicInfo.classList.remove("active");
        placementPreferences.classList.add("active");
    });

    document.getElementById("toAcademicInfoBack").addEventListener("click", function() {
        placementPreferences.classList.remove("active");
        academicInfo.classList.add("active");
    });

    // Save buttons
    document.getElementById("personalInfoSave").addEventListener("click", function() {
        alert("Personal Information saved.");
    });

    document.getElementById("demographicsSave").addEventListener("click", function() {
        alert("Demographics saved.");
    });

    document.getElementById("academicInfoSave").addEventListener("click", function() {
        alert("Academic Information saved.");
    });

    document.getElementById("placementPreferencesSave").addEventListener("click", function() {
        alert("Placement Preferences saved.");
    });

    // Initially show the first category
    personalInfo.classList.add("active");
});
