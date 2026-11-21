
const floorSelect = document.getElementById("floor");
const locationSelect = document.getElementById("location-lost");

// List of locations for each floor
const locations = {
    "Level 2": [
        "Toilets",
        "Cloud Computing Laboratory",
        "Hotel Mock Room",
        "Start Up Incubator",
        "Bilik MDF",
        "Broadcast Studio",
        "Admin and Facilities Management(AFM)",
        "Cheif Executive Office",
        "Looby",
        "Finance Office",
        "University Placement Office",
        "Artificial Intelligence Laboratory",
        "Register Office",
        "Alkira Restaurant",
        "Training Kitchen",
        "Production Kitchen",
        "Pastry Kitchen",
        "Reception",
        "Cafeteria"
    ],


    "Level 3": [
        "Convenience Store", 
        "Toilets",
        "Academic Office",
        "Career Service office ",
        "Cubicles ",
        "Academic Affairs Office (AAO)",
        "Centre of American Education (CAE)",
        "Solid Mechanics Lab",
        "Centre of Excellence for Pre University studies(CEPS)",
        "Aristotle Room",
        "Student Experience Office ",
        "Network Lab",
        "Information Technology Services ",
        "IT Lab B",
        "IT Lab A",
        "Thermofluid Lab",
        "School of Business (SOBIZ)",
        "Corporate Training",
        "Human Resources Office"
    ],

    "Level 4": [
        "Project Lab 1",
        "Electrical Lab",
        "Motorola Solutions Embedded Lab",
        "Physics Lab 1",
        "Biology Lab",
        "Science Preparation Lab ",
        "Chemistry Lab 1",
        "Chemistry Lab 2",
        "Study Cubicles",
        "Science Lab Store",
        "Design Simulation Lab",
        "LR401 A",
        "LR401",
        "LR402",
        "LR403",
        "LR404",
        "LR405",
        "LR406",
        "LR407",
        "Project Lab 2",
        "Teletronic Lab",
        "Toilets"
    ],

    "Level 5": [
        "LR510B",
        "SURAU PEREMPUAN",
        "SURAU LELAKI",
        "LR511",
        "LECTURE THEATURE",
        "LR512",
        "LR513",
        "LR514",
        "IMAC Lab",
        "Mock Reception",
        "LR501",
        "LR502",
        "LR503",
        "LR504",
        "LR505",
        "LR506",
        "LR507",
        "LR508",
        "LR 509B",
        "STEM LAB",
        "LR510A",
        "Toilets"
    ],

    "Level 6": [
        "LR606",
        "LR605",
        "MULTI PURPOSE HALL(MPH)",
        "CAFETERIA",
        "STUDENT COUNSELING SERVICES ",
        "LR601",
        "LR602",
        "LR603",
        "STUDY AREA ",
        "EXAM ROOM" ,
        "LR604",
        "LIBRARY ",
        "TOILET"
    ],

    "Others": [
        "Rooftop",
        "Level 1 Car park",
        "Underground Car pack",
        "Basketball Court",
        "Futsal Court"
    ],
};

// Update locations when a floor is selected
floorSelect.addEventListener("change", function () {

    const selectedFloor = this.value;

    locationSelect.innerHTML =
        '<option value="">Select specific location</option>';

    if (selectedFloor !== "") {

        locations[selectedFloor].sort().forEach(function (place) {

            const option = document.createElement("option");

            option.value = place;
            option.textContent = place;

            locationSelect.appendChild(option);

        });

        locationSelect.disabled = false;

    } else {

        locationSelect.disabled = true;

    }

});
