document.addEventListener("DOMContentLoaded", function () {

    /* =========================================
       FOODS PAGE - FILTERS
    ========================================= */

    const foodCards = document.querySelectorAll(".food-card");
    const foodTabs = document.querySelectorAll(".food-tab");
    const ageFilter = document.querySelector(".age-filter");

    let selectedCategory = "All";

    function filterFoods() {
        if (!foodCards.length) return;

        const selectedAgeValue = ageFilter
            ? ageFilter.value
            : "All Ages";

        const selectedAge =
            selectedAgeValue === "All Ages"
                ? null
                : parseInt(selectedAgeValue, 10);

        foodCards.forEach(function (card) {

            const category =
                card.dataset.category || "";

            const ageFrom =
                parseInt(card.dataset.ageFrom || "0", 10);

            const categoryMatch =
                selectedCategory === "All" ||
                category === selectedCategory;

            const ageMatch =
                selectedAge === null ||
                ageFrom <= selectedAge;

            card.style.display =
                categoryMatch && ageMatch
                    ? ""
                    : "none";
        });
    }


    /* =========================================
       CATEGORY TABS
    ========================================= */

    foodTabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            foodTabs.forEach(function (item) {
                item.classList.remove("active");
            });

            tab.classList.add("active");

            selectedCategory =
                tab.dataset.category || "All";

            filterFoods();
        });

    });


    /* =========================================
       AGE FILTER
    ========================================= */

    if (ageFilter) {

        ageFilter.addEventListener(
            "change",
            filterFoods
        );

    }


    /* =========================================
       FOOD DETAILS
    ========================================= */

    const detailsButtons =
        document.querySelectorAll(
            ".food-details-btn"
        );

    const detailsModalElement =
        document.getElementById(
            "foodDetailsModal"
        );

    const detailsTitle =
        document.getElementById(
            "foodDetailsTitle"
        );

    const detailsImage =
        document.getElementById(
            "foodDetailsImage"
        );

    const detailsCategory =
        document.getElementById(
            "foodDetailsCategory"
        );

    const detailsAge =
        document.getElementById(
            "foodDetailsAge"
        );

    const detailsDescription =
        document.getElementById(
            "foodDetailsDescription"
        );

    const detailsServing =
        document.getElementById(
            "foodDetailsServing"
        );

    const detailsNote =
        document.getElementById(
            "foodDetailsNote"
        );

    const recordMealLink =
        document.getElementById(
            "recordMealFromDetails"
        );


    /* =========================================
       FOOD DATA
    ========================================= */

    const foodDetails = {

        banana: {
            name: "Banana",
            category: "Fruits",
            age: "6+ Months",
            image: "../assets/images/banana.png",
            description:
                "Soft and naturally sweet, rich in potassium and easy for babies to eat.",
            serving:
                "Mashed or softly sliced.",
            note:
                "Use a soft, baby-safe texture."
        },

        apple: {
            name: "Apple",
            category: "Fruits",
            age: "6+ Months",
            image: "../assets/images/apple.png",
            description:
                "A nutritious fruit that can be served cooked or as a smooth puree.",
            serving:
                "Cooked and softened, or smooth puree.",
            note:
                "Avoid hard raw pieces for young babies."
        },

        avocado: {
            name: "Avocado",
            category: "Fruits",
            age: "6+ Months",
            image: "../assets/images/avocado.png",
            description:
                "A creamy food rich in healthy fats and important nutrients.",
            serving:
                "Mashed or cut into soft baby-safe pieces.",
            note:
                "Choose a ripe, soft avocado."
        },

        carrot: {
            name: "Carrot",
            category: "Vegetables",
            age: "6+ Months",
            image: "../assets/images/carrot.png",
            description:
                "A source of beta-carotene that works well when steamed and pureed.",
            serving:
                "Steamed until soft, then mashed or pureed.",
            note:
                "Cook until very soft."
        },

        "sweet-potato": {
            name: "Sweet Potato",
            category: "Vegetables",
            age: "6+ Months",
            image: "../assets/images/sweet-potato.png",
            description:
                "Naturally sweet, soft and packed with vitamins and fiber.",
            serving:
                "Baked or steamed, then mashed.",
            note:
                "Serve soft and easy to mash."
        },

        broccoli: {
            name: "Broccoli",
            category: "Vegetables",
            age: "6+ Months",
            image: "../assets/images/broccoli.png",
            description:
                "A nutrient-rich vegetable that can be steamed until soft.",
            serving:
                "Steamed until tender and mashed or finely chopped.",
            note:
                "Make sure the texture is soft."
        },

        oats: {
            name: "Oats",
            category: "Grains",
            age: "6+ Months",
            image: "../assets/images/oats.png",
            description:
                "A filling grain that can be prepared as a smooth baby-friendly porridge.",
            serving:
                "Cooked into a soft porridge.",
            note:
                "Prepare with a baby-appropriate texture."
        },

        "rice-cereal": {
            name: "Rice Cereal",
            category: "Grains",
            age: "6+ Months",
            image: "../assets/images/rice-cereal.png",
            description:
                "A simple grain option with a soft texture suitable for early feeding.",
            serving:
                "Prepared as a smooth cereal.",
            note:
                "Keep the texture soft and smooth."
        },

        chicken: {
            name: "Chicken",
            category: "Proteins",
            age: "6+ Months",
            image: "../assets/images/chicken.png",
            description:
                "A good source of protein and iron when cooked thoroughly and served softly.",
            serving:
                "Finely shredded, minced, or pureed.",
            note:
                "Cook thoroughly before serving."
        },

        egg: {
            name: "Egg",
            category: "Proteins",
            age: "6+ Months",
            image: "../assets/images/egg.png",
            description:
                "A nutrient-dense food that should be cooked thoroughly before serving.",
            serving:
                "Fully cooked and softly prepared.",
            note:
                "Introduce carefully and watch for reactions."
        },

        lentils: {
            name: "Lentils",
            category: "Proteins",
            age: "6+ Months",
            image: "../assets/images/lentils.png",
            description:
                "A plant-based source of protein and iron with a soft texture when cooked.",
            serving:
                "Cooked until soft and mashed.",
            note:
                "Cook thoroughly for a soft texture."
        },

        yogurt: {
            name: "Yogurt",
            category: "Dairy",
            age: "6+ Months",
            image: "../assets/images/yogurt.png",
            description:
                "Plain full-fat yogurt can provide calcium and protein for growing babies.",
            serving:
                "Plain, smooth yogurt.",
            note:
                "Choose plain yogurt without added sugar."
        }

    };


    /* =========================================
       OPEN FOOD DETAILS
    ========================================= */

    function openFoodDetails(slug) {

        const food =
            foodDetails[slug];

        if (
            !food ||
            !detailsModalElement
        ) {
            return;
        }

        detailsTitle.textContent =
            food.name;

        detailsImage.src =
            food.image;

        detailsImage.alt =
            food.name;

        detailsCategory.textContent =
            food.category;

        detailsAge.textContent =
            food.age;

        detailsDescription.textContent =
            food.description;

        detailsServing.textContent =
            food.serving;

        detailsNote.textContent =
            food.note;


        /* Record This Meal */

        if (recordMealLink) {

            recordMealLink.href =
                "daily-tracker.php?record=meal&food=" +
                encodeURIComponent(slug);

        }


        /* Open Bootstrap Modal */

        if (
            typeof bootstrap !== "undefined"
        ) {

            const modal =
                bootstrap.Modal
                    .getOrCreateInstance(
                        detailsModalElement
                    );

            modal.show();

        }

    }


    /* =========================================
       VIEW DETAILS BUTTONS
    ========================================= */

    detailsButtons.forEach(function (button) {

        button.addEventListener(
            "click",
            function () {

                const slug =
                    button.dataset.food;

                openFoodDetails(slug);

            }
        );

    });



const feedingType = document.getElementById("feedingType");
const foodSelectionGroup = document.getElementById("foodSelectionGroup");
const foodId = document.getElementById("foodId");

if (feedingType) {

    feedingType.addEventListener("change", function () {

        if (this.value === "Solid food") {

            foodSelectionGroup.style.display = "block";

        } else {

            foodSelectionGroup.style.display = "none";
            foodId.value = "";

        }

    });

}

});

//============================================vaccination page ==================================================================


document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       1. VACCINATION STATUS TABS
    ===================================================== */

    const vaccinationTabs =
        document.querySelectorAll(".vaccination-tab");

    const vaccinationCards =
        document.querySelectorAll(".vaccination-card");


    vaccinationTabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            /* Remove active from all tabs */

            vaccinationTabs.forEach(function (item) {
                item.classList.remove("active");
            });


            /* Add active to clicked tab */

            tab.classList.add("active");


            const selectedStatus =
                tab.dataset.status;


            /* Show / hide vaccination cards */

            vaccinationCards.forEach(function (card) {

                const cardStatus =
                    card.dataset.status;


                if (
                    selectedStatus === "all" ||
                    cardStatus === selectedStatus
                ) {

                    card.style.display = "";

                } else {

                    card.style.display = "none";

                }

            });

        });

    });



    /* =====================================================
       2. BABY SELECTOR
    ===================================================== */

    const babySelector =
        document.querySelector(
            ".vaccination-baby-selector"
        );

    const babyInfo =
        document.querySelector(
            ".vaccination-baby-info"
        );


    if (babySelector) {

        /* Create dropdown */

        const babyDropdown =
            document.createElement("div");

        babyDropdown.className =
            "vaccination-baby-dropdown";


        const babies = JSON.parse(
    babySelector.dataset.babies || "[]"
);

babies.forEach(function (baby, index) {

    const option = document.createElement("button");

    option.type = "button";
    option.className =
        "vaccination-baby-option" +
        (index === 0 ? " active" : "");

    option.dataset.baby = baby.name;
    option.dataset.babyId = baby.id;

    // Calculate age
    const birthDate = new Date(baby.birth_date);
    const today = new Date();

    let years =
        today.getFullYear() -
        birthDate.getFullYear();

    let months =
        today.getMonth() -
        birthDate.getMonth();

    if (months < 0) {
        years--;
        months += 12;
    }

    if (
        today.getDate() <
        birthDate.getDate()
    ) {
        months--;

        if (months < 0) {
            months = 11;
            years--;
        }
    }

    let ageText = "";

    if (years > 0) {

        ageText =
            years +
            (years === 1 ? " Year" : " Years");

        if (months > 0) {
            ageText +=
                " " +
                months +
                (months === 1
                    ? " Month"
                    : " Months");
        }

    } else {

        ageText =
            months +
            (months === 1
                ? " Month"
                : " Months");
    }

    option.dataset.age =
        ageText + " Old";

    option.innerHTML = `
        <span>${baby.name}</span>
        <i class="bi bi-check"></i>
    `;

    babyDropdown.appendChild(option);

});


        /* Position the dropdown */

        babySelector.parentElement.style.position =
            "relative";


        babyDropdown.style.position =
            "absolute";

        babyDropdown.style.right =
            "0";

        babyDropdown.style.top =
            "calc(100% + 8px)";

        babyDropdown.style.width =
            "150px";

        babyDropdown.style.background =
            "#FFFFFF";

        babyDropdown.style.border =
            "1px solid #E5E7EB";

        babyDropdown.style.borderRadius =
            "10px";

        babyDropdown.style.padding =
            "6px";

        babyDropdown.style.boxShadow =
            "0 10px 25px rgba(31, 41, 55, 0.08)";

        babyDropdown.style.zIndex =
            "1000";

        babyDropdown.style.display =
            "none";


        babySelector.parentElement.appendChild(
            babyDropdown
        );


        /* Open / close dropdown */

        babySelector.addEventListener(
            "click",
            function (event) {

                event.stopPropagation();

                babyDropdown.style.display =
                    babyDropdown.style.display === "none"
                        ? "block"
                        : "none";

            }
        );


        /* Select baby */

        const babyOptions =
            babyDropdown.querySelectorAll(
                ".vaccination-baby-option"
            );


        babyOptions.forEach(function (option) {

            option.addEventListener(
                "click",
                function () {

                    const selectedBaby =
                        option.dataset.baby;

                    const selectedAge =
                        option.dataset.age;


                    /* Update selector */

                    const selectorText =
                        babySelector.querySelector(
                            "span"
                        );

                    if (selectorText) {
                        selectorText.textContent =
                            selectedBaby;
                    }


                    /* Update baby information */

                    if (babyInfo) {

                        const nameElement =
                            babyInfo.querySelector(
                                "strong"
                            );

                        const ageElement =
                            babyInfo.querySelector(
                                "span"
                            );


                        if (nameElement) {
                            nameElement.textContent =
                                selectedBaby;
                        }


                        if (ageElement) {
                            ageElement.textContent =
                                selectedAge;
                        }

                    }


                    /* Update selected option */

                    babyOptions.forEach(
                        function (item) {

                            item.classList.remove(
                                "active"
                            );

                        }
                    );

                    option.classList.add("active");


                    /* Close dropdown */

                    babyDropdown.style.display =
                        "none";

                }
            );

        });


        /* Close when clicking outside */

        document.addEventListener(
            "click",
            function (event) {

                if (
                    !babySelector.contains(event.target) &&
                    !babyDropdown.contains(event.target)
                ) {

                    babyDropdown.style.display =
                        "none";

                }

            }
        );

    }



    /* =====================================================
       3. VACCINATION DETAILS MODAL
    ===================================================== */

    const detailsButtons =
        document.querySelectorAll(
            ".vaccination-details-btn"
        );


    const detailsModalElement =
        document.getElementById(
            "vaccinationDetailsModal"
        );


    const detailsTitle =
        document.getElementById(
            "vaccinationDetailsTitle"
        );


    /* =====================================================
       CURRENT SELECTED VACCINE
       Used when opening Edit Record
    ===================================================== */

    let currentVaccineKey = null;


    /* =====================================================
       VACCINATION DATA
    ===================================================== */

    const detailsData = {

        bcg: {
            name: "BCG",
            status: "Completed",
            statusClass: "completed",
            statusIcon: "bi-check-circle-fill",
            recommended: "At Birth",
            dueDate: "Jan 10, 2025",
            dateTaken: "Jan 10, 2025",
            description:
                "Protects against tuberculosis.",
            notes:
                "No notes added."
        },


        "hepatitis-b-1": {
            name: "Hepatitis B (1st dose)",
            status: "Completed",
            statusClass: "completed",
            statusIcon: "bi-check-circle-fill",
            recommended: "At Birth",
            dueDate: "Jan 10, 2025",
            dateTaken: "Jan 10, 2025",
            description:
                "Protects against hepatitis B.",
            notes:
                "No notes added."
        },


        "hepatitis-b-2": {
            name: "Hepatitis B (2nd dose)",
            status: "Completed",
            statusClass: "completed",
            statusIcon: "bi-check-circle-fill",
            recommended: "At 1 Month",
            dueDate: "Feb 10, 2025",
            dateTaken: "Feb 10, 2025",
            description:
                "Continues protection against hepatitis B.",
            notes:
                "No notes added."
        },


        "hepatitis-b-3": {
            name: "Hepatitis B (3rd dose)",
            status: "Upcoming",
            statusClass: "upcoming",
            statusIcon: "bi-clock-fill",
            recommended: "At 6 Months",
            dueDate: "Oct 10, 2025",
            dateTaken: "Not taken yet",
            description:
                "Completes the hepatitis B series.",
            notes:
                "Vaccination has not been recorded yet."
        },


        dtap: {
            name: "DTaP",
            status: "Upcoming",
            statusClass: "upcoming",
            statusIcon: "bi-clock-fill",
            recommended: "At 2 Months",
            dueDate: "Dec 10, 2025",
            dateTaken: "Not taken yet",
            description:
                "Protects against diphtheria, tetanus, and pertussis.",
            notes:
                "Vaccination has not been recorded yet."
        },


        ipv: {
            name: "IPV (Polio)",
            status: "Overdue",
            statusClass: "overdue",
            statusIcon:
                "bi-exclamation-triangle-fill",
            recommended: "At 2 Months",
            dueDate: "Sep 10, 2025",
            dateTaken: "Not taken yet",
            description:
                "Protects against polio.",
            notes:
                "This vaccination has not been recorded."
        },


        mmr: {
            name: "MMR",
            status: "Not Due",
            statusClass: "not-due",
            statusIcon: "bi-clock",
            recommended: "At 12 Months",
            dueDate: "Jan 10, 2026",
            dateTaken: "Not taken yet",
            description:
                "Protects against measles, mumps, and rubella.",
            notes:
                "This vaccination is not due yet."
        }

    };



    /* =====================================================
       4. OPEN DETAILS
    ===================================================== */

    detailsButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    const vaccineKey =
                        button.dataset.vaccine;


                    const vaccine =
                        detailsData[vaccineKey];


                    if (
                        !vaccine ||
                        !detailsModalElement
                    ) {
                        return;
                    }


                    /* Save current vaccine */

                    currentVaccineKey =
                        vaccineKey;


                    /* Title */

                    if (detailsTitle) {

                        detailsTitle.textContent =
                            vaccine.name;

                    }


                    /* Status */

                    const statusElement =
                        detailsModalElement.querySelector(
                            ".vaccination-modal-status .vaccine-status"
                        );


                    if (statusElement) {

                        statusElement.className =
                            "vaccine-status " +
                            vaccine.statusClass;


                        statusElement.innerHTML = `

                            <i class="bi ${vaccine.statusIcon}"></i>

                            ${vaccine.status}

                        `;

                    }


                    /* Detail Grid */

                    const detailBoxes =
                        detailsModalElement.querySelectorAll(
                            ".vaccination-detail-grid > div"
                        );


                    if (detailBoxes.length >= 4) {

                        /* Recommended Age */

                        const recommendedElement =
                            detailBoxes[0].querySelector(
                                "strong"
                            );

                        if (recommendedElement) {

                            recommendedElement.textContent =
                                vaccine.recommended;

                        }


                        /* Due Date */

                        const dueDateElement =
                            detailBoxes[1].querySelector(
                                "strong"
                            );

                        if (dueDateElement) {

                            dueDateElement.textContent =
                                vaccine.dueDate;

                        }


                        /* Date Taken */

                        const dateTakenElement =
                            detailBoxes[2].querySelector(
                                "strong"
                            );

                        if (dateTakenElement) {

                            dateTakenElement.textContent =
                                vaccine.dateTaken;

                        }


                        /* Vaccine */

                        const vaccineElement =
                            detailBoxes[3].querySelector(
                                "strong"
                            );

                        if (vaccineElement) {

                            vaccineElement.textContent =
                                vaccine.name;

                        }

                    }


                    /* Description */

                    const description =
                        detailsModalElement.querySelector(
                            ".vaccination-modal-description p"
                        );


                    if (description) {

                        description.textContent =
                            vaccine.description;

                    }


                    /* Notes */

                    const notes =
                        detailsModalElement.querySelector(
                            ".vaccination-modal-notes p"
                        );


                    if (notes) {

                        notes.textContent =
                            vaccine.notes;

                    }


                    /* Open Bootstrap Modal */

                    if (
                        typeof bootstrap !==
                        "undefined"
                    ) {

                        const modal =
                            bootstrap.Modal
                                .getOrCreateInstance(
                                    detailsModalElement
                                );

                        modal.show();

                    }

                }
            );

        }
    );



    /* =====================================================
       5. EDIT RECORD
    ===================================================== */

    const recordModalElement =
        document.getElementById(
            "recordVaccinationModal"
        );


    const vaccineSelect =
        document.getElementById(
            "vaccineSelect"
        );


    const dateTakenInput =
        document.getElementById(
            "dateTaken"
        );


    const vaccinationNotes =
        document.getElementById(
            "vaccinationNotes"
        );


    /*
       Find the existing Edit Record button
       inside the Details Modal.

       We don't depend only on a special class,
       because the current HTML doesn't have it yet.
    */

    let editRecordButton = null;


    if (detailsModalElement) {

        editRecordButton =
            detailsModalElement.querySelector(
                ".vaccination-edit-record"
            );


        /*
           If the class doesn't exist,
           find the button by its text.
        */

        if (!editRecordButton) {

            const modalButtons =
                detailsModalElement.querySelectorAll(
                    "button"
                );


            modalButtons.forEach(
                function (button) {

                    if (
                        button.textContent
                            .trim()
                            .includes("Edit Record")
                    ) {

                        editRecordButton =
                            button;

                    }

                }
            );

        }

    }



    /*
       Convert:

       Jan 10, 2025

       into:

       2025-01-10

       because <input type="date">
       needs this format.
    */

    function convertDateToInputFormat(dateText) {

        if (
            !dateText ||
            dateText === "Not taken yet"
        ) {

            return "";

        }


        const date =
            new Date(dateText);


        if (isNaN(date.getTime())) {

            return "";

        }


        const year =
            date.getFullYear();


        const month =
            String(
                date.getMonth() + 1
            ).padStart(2, "0");


        const day =
            String(
                date.getDate()
            ).padStart(2, "0");


        return (
            year +
            "-" +
            month +
            "-" +
            day
        );

    }



    /*
       Open Record Vaccination
       with selected vaccine data.
    */

    if (editRecordButton) {

        editRecordButton.addEventListener(
            "click",
            function () {

                if (
                    !currentVaccineKey ||
                    !detailsData[currentVaccineKey]
                ) {

                    return;

                }


                const vaccine =
                    detailsData[currentVaccineKey];


                /* Fill Vaccine */

                if (vaccineSelect) {

                    vaccineSelect.value =
                        vaccine.name;

                }


                /* Fill Date Taken */

                if (dateTakenInput) {

                    dateTakenInput.value =
                        convertDateToInputFormat(
                            vaccine.dateTaken
                        );

                }


                /* Fill Notes */

                if (vaccinationNotes) {

                    if (
                        vaccine.notes ===
                        "No notes added."
                    ) {

                        vaccinationNotes.value =
                            "";

                    } else {

                        vaccinationNotes.value =
                            vaccine.notes;

                    }

                }


                /*
                   Close Details Modal first,
                   then open Record Modal.
                */

                if (
                    detailsModalElement &&
                    typeof bootstrap !==
                    "undefined"
                ) {

                    const detailsModal =
                        bootstrap.Modal
                            .getOrCreateInstance(
                                detailsModalElement
                            );


                    detailsModalElement.addEventListener(
                        "hidden.bs.modal",
                        function openRecordModal() {

                            detailsModalElement.removeEventListener(
                                "hidden.bs.modal",
                                openRecordModal
                            );


                            if (recordModalElement) {

                                const recordModal =
                                    bootstrap.Modal
                                        .getOrCreateInstance(
                                            recordModalElement
                                        );

                                recordModal.show();

                            }

                        }
                    );


                    detailsModal.hide();

                }

            }
        );

    }



    /* =====================================================
       6. RECORD VACCINATION FORM
    ===================================================== */




    /* =====================================================
       7. NEW RECORD BUTTON
    ===================================================== */

    /*
       When the user clicks the main
       "Record Vaccination" button,
       make sure the form opens empty.
    */

    const newRecordButton =
        document.querySelector(
            '[data-bs-target="#recordVaccinationModal"]'
        );


    if (newRecordButton) {

        newRecordButton.addEventListener(
            "click",
            function () {

                currentVaccineKey = null;


                if (recordForm) {

                    recordForm.reset();

                }

            }
        );

    }



    /* =====================================================
       8. SEARCH VACCINATIONS
    ===================================================== */

    const searchInput =
        document.querySelector(
            ".dashboard-search input"
        );


    if (searchInput) {

        searchInput.addEventListener(
            "input",
            function () {

                const searchValue =
                    searchInput.value
                        .trim()
                        .toLowerCase();


                vaccinationCards.forEach(
                    function (card) {

                        const vaccineName =
                            card.querySelector(
                                ".vaccination-card-info h3"
                            );


                        if (!vaccineName) {
                            return;
                        }


                        const name =
                            vaccineName.textContent
                                .toLowerCase();


                        if (
                            name.includes(
                                searchValue
                            )
                        ) {

                            card.style.display =
                                "";

                        } else {

                            card.style.display =
                                "none";

                        }

                    }
                );

            }
        );

    }


});



/* ================================================================
   DAILY TRACKER PAGE
================================================================ */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       CHECK IF DAILY TRACKER PAGE
    ===================================================== */

    const dailyTrackerPage =
        document.querySelector(".daily-tracker-page");

    if (!dailyTrackerPage) {
        return;
    }


    /* =====================================================
       ELEMENTS
    ===================================================== */

    const babySelector =
        document.getElementById("dailyBabySelector");

    const babyDropdown =
        document.getElementById("dailyBabyDropdown");

    const selectedBabyName =
        document.getElementById("selectedBabyName");

    const selectedBabyAge =
        document.getElementById("selectedBabyAge");

    const babyOptions =
        document.querySelectorAll(
            ".daily-baby-option"
        );


    const dateButton =
        document.getElementById("dailyDateBtn");

    const dateInput =
        document.getElementById("dailyDateInput");

    const dateText =
        document.getElementById("dailyDateText");


    const searchInput =
        document.getElementById("dailyTrackerSearch");


    const recordsList =
        document.getElementById("dailyRecordsList");

    const recordsCount =
        document.getElementById("recordsCount");

    const emptyState =

    document.getElementById("dailyEmptyState");


    const recordModalElement =
        document.getElementById("recordModal");

    const recordModalTitle =
        document.getElementById("recordModalTitle");

    const recordForm =
        document.getElementById("recordForm");

    const recordType =
        document.getElementById("recordType");

    const recordTime =
        document.getElementById("recordTime");

    const sleepFields =
        document.getElementById("sleepFields");

    const feedingFields =
        document.getElementById("feedingFields");

    const diaperFields =
        document.getElementById("diaperFields");

    const noteFields =
        document.getElementById("noteFields");

    const singleTimeGroup =
        document.getElementById("singleTimeGroup");

    const saveRecordText =
        document.getElementById("saveRecordText");


    let editingRecord = null;


    /* =====================================================
       BABY SELECTOR
    ===================================================== */

    if (babySelector && babyDropdown) {

        babySelector.addEventListener(
            "click",
            function (event) {

                event.preventDefault();
                event.stopPropagation();

                babyDropdown.classList.toggle("show");

            }
        );


        babyOptions.forEach(
            function (option) {

                option.addEventListener(
                    "click",
                    function (event) {

                        event.preventDefault();
                        event.stopPropagation();


                        const babyName =
                            option.dataset.baby;


                        const babyAge =
                            option.dataset.age;


                        /* Update selected baby */
                        if (selectedBabyName) {

                            selectedBabyName.textContent =
                                babyName;

                        }


                        /* Update baby age */
                        if (selectedBabyAge) {

                            selectedBabyAge.textContent =
                                babyAge;

                        }


                        /* Update selector text */
                        const selectorText =
                            babySelector.querySelector(
                                "span"
                            );


                        if (selectorText) {

                            selectorText.textContent =
                                babyName;

                        }


                        /* Update active option */
                        babyOptions.forEach(
                            function (item) {

                                item.classList.remove(
                                    "active"
                                );

                            }
                        );


                        option.classList.add(
                            "active"
                        );
                        /* Update URL with selected baby */

                              const babyId = option.dataset.babyId;

                              const url = new URL(window.location.href);

                              url.searchParams.set("baby_id", babyId);

                              window.location.href = url.toString();



                        /* Close dropdown */
                        babyDropdown.classList.remove(
                            "show"
                        );

                    }
                );

            }
        );

    }


    /* =====================================================
       CLOSE BABY DROPDOWN
    ===================================================== */

    document.addEventListener(
        "click",
        function (event) {

            if (
                babyDropdown &&
                !event.target.closest(
                    ".daily-baby-selector-wrapper"
                )
            ) {

                babyDropdown.classList.remove(
                    "show"
                );

            }

        }
    );


    /* =====================================================
       DATE SELECTOR
    ===================================================== */

    if (dateButton && dateInput) {

        dateButton.addEventListener(
            "click",
            function () {

                if (
                    typeof dateInput.showPicker ===
                    "function"
                ) {

                    dateInput.showPicker();

                } else {

                    dateInput.click();

                }

            }
        );


        dateInput.addEventListener(
            "change",
            function () {

                if (!dateInput.value) {
                    return;
                }


                const selectedDate =
                    new Date(
                        dateInput.value +
                        "T00:00:00"
                    );


                const formattedDate =
                    selectedDate.toLocaleDateString(
                        "en-US",
                        {
                            weekday: "short",
                            month: "short",
                            day: "numeric",
                            year: "numeric"
                        }
                    );

                    const url = new URL(window.location.href);

                     url.searchParams.set("date", dateInput.value);

                      window.location.href = url.toString();


                if (dateText) {

                    dateText.textContent =
                        formattedDate;

                }

            }
        );

    }


    /* =====================================================
       RECORD TYPE
    ===================================================== */

    function updateRecordFields() {

        if (!recordType) {
            return;
        }


        const type =
            recordType.value;


        /* Hide dynamic sections */
        if (sleepFields) {
            sleepFields.classList.remove("active");
        }

        if (feedingFields) {
            feedingFields.classList.remove("active");
        }

        if (diaperFields) {
            diaperFields.classList.remove("active");
        }

        if (noteFields) {
            noteFields.classList.remove("active");
        }


        /* Show normal time field */
        if (singleTimeGroup) {

            singleTimeGroup.style.display =
                "block";

        }


        /* Sleep */
        if (type === "sleep") {

            if (singleTimeGroup) {

                singleTimeGroup.style.display =
                    "none";

            }

            if (sleepFields) {

                sleepFields.classList.add(
                    "active"
                );

            }

        }


        /* Feeding */
        if (type === "feeding") {

            if (feedingFields) {

                feedingFields.classList.add(
                    "active"
                );

            }

        }


        /* Diaper */
        if (type === "diaper") {

            if (diaperFields) {

                diaperFields.classList.add(
                    "active"
                );

            }

        }


        /* Note */
        if (type === "note") {

            if (noteFields) {

                noteFields.classList.add(
                    "active"
                );

            }

        }

    }


    if (recordType) {

        recordType.addEventListener(
            "change",
            updateRecordFields
        );

    }


    /* =====================================================
       RESET FORM
    ===================================================== */

    function resetRecordForm() {

        if (!recordForm) {
            return;
        }


        recordForm.reset();


        editingRecord = null;


        if (recordModalTitle) {

            recordModalTitle.textContent =
                "Add New Record";

        }


        if (saveRecordText) {

            saveRecordText.textContent =
                "Save Record";

        }


        updateRecordFields();

    }


    /* =====================================================
       ADD RECORD BUTTON
    ===================================================== */

    const addRecordButtons =
        document.querySelectorAll(
            '[data-bs-target="#recordModal"]'
        );


    addRecordButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    resetRecordForm();

                }
            );

        }
    );


    /* =====================================================
       RECORD MENU
       EDIT / DELETE
    ===================================================== */

    if (recordsList) {

        recordsList.addEventListener(
            "click",
            function (event) {

                const moreButton =
                    event.target.closest(
                        ".daily-more-btn"
                    );


                const editButton =
                    event.target.closest(
                        ".daily-edit-record"
                    );


                const deleteButton =
                    event.target.closest(
                        ".daily-delete-record"
                    );


                /* -----------------------------------------
                   MORE BUTTON
                ----------------------------------------- */

                if (moreButton) {

                    event.preventDefault();
                    event.stopPropagation();


                    const currentRecord =
                        moreButton.closest(
                            ".daily-record-item"
                        );


                    if (!currentRecord) {
                        return;
                    }


                    const currentMenu =
                        currentRecord.querySelector(
                            ".daily-record-dropdown"
                        );


                    if (!currentMenu) {
                        return;
                    }


                    /* Close other menus */
                    recordsList
                        .querySelectorAll(
                            ".daily-record-dropdown"
                        )
                        .forEach(
                            function (menu) {

                                if (
                                    menu !== currentMenu
                                ) {

                                    menu.classList.remove(
                                        "show"
                                    );

                                }

                            }
                        );


                    /* Open / close current menu */
                    currentMenu.classList.toggle(
                        "show"
                    );


                    return;

                }


                /* -----------------------------------------
                   EDIT BUTTON
                ----------------------------------------- */

                if (editButton) {

                    event.preventDefault();
                    event.stopPropagation();


                    const currentRecord =
                        editButton.closest(
                            ".daily-record-item"
                        );


                    if (!currentRecord) {
                        return;
                    }


                    openEditRecord(
                        currentRecord
                    );


                    return;

                }


                /* -----------------------------------------
                   DELETE BUTTON
                ----------------------------------------- */

                if (deleteButton) {

                    event.preventDefault();
                    event.stopPropagation();


                    const currentRecord =
                        deleteButton.closest(
                            ".daily-record-item"
                        );


                    if (!currentRecord) {
                        return;
                    }


                    deleteRecord(
                        currentRecord
                    );

                }

            }
        );

    }


    /* =====================================================
       CLOSE RECORD MENUS
    ===================================================== */

    document.addEventListener(
        "click",
        function (event) {

            if (
                !event.target.closest(
                    ".daily-record-menu"
                )
            ) {

                document
                    .querySelectorAll(
                        ".daily-record-dropdown"
                    )
                    .forEach(
                        function (menu) {

                            menu.classList.remove(
                                "show"
                            );

                        }
                    );

            }

        }
    );


    /* =====================================================
       OPEN EDIT RECORD
    ===================================================== */

    function openEditRecord(record) {
        const recordId = record.dataset.id;

        editingRecord =
            record;


        const type =
            record.dataset.type;


        if (recordModalTitle) {

            recordModalTitle.textContent =
                "Edit Record";

        }


        if (saveRecordText) {

            saveRecordText.textContent =
                "Update Record";

        }


        if (recordType) {

            recordType.value =
                type;

        }


        updateRecordFields();


        const timeElement =
            record.querySelector(
                ".daily-record-time"
            );


        const detailsElement =
            record.querySelector(
                ".daily-record-details p"
            );


        const detailsText =
            detailsElement
                ? detailsElement.textContent.trim()
                : "";

/* ----------------------------------------- SLEEP ----------------------------------------- */
        if (type === "sleep") {

    const startInput =
        document.getElementById("sleepStart");

    const endInput =
        document.getElementById("sleepEnd");

    const startTime =
        record.dataset.startTime;

    const endTime =
        record.dataset.endTime;

    if (startInput && startTime) {
        startInput.value =
            startTime.substring(11, 16);
    }

    if (endInput && endTime) {
        endInput.value =
            endTime.substring(11, 16);
    }

}

      /* -----------------------------------------
   FEEDING
----------------------------------------- */

if (type === "feeding") {

    const feedingType =
        document.getElementById("feedingType");

    const feedingQuantity =
        document.getElementById("feedingQuantity");

    const reactionInput =
        document.getElementById("reaction");

    const startTime =
        record.dataset.startTime || "";

    const notes =
        record.dataset.notes || "";


    /* Time */

    if (recordTime && startTime) {

        recordTime.value =
            startTime.substring(11, 16);

    }


    /* Feeding Type + Quantity */

    const parts =
        detailsText.split(" - ");

    if (feedingType) {

        feedingType.value =
            parts[0] ? parts[0].trim() : "";

    }

    if (feedingQuantity) {

        feedingQuantity.value =
            parts.slice(1).join(" - ").trim();

    }


    /* Reaction */

    if (reactionInput) {

        const reactionMatch =
            notes.match(/Reaction:\s*(.*)/i);

        if (reactionMatch) {

            reactionInput.value =
                reactionMatch[1].trim();

        } else {

            reactionInput.value = "";

        }

    }

}

/* -----------------------------------------
   DIAPER
----------------------------------------- */

if (type === "diaper") {

    const startTime =
        record.dataset.startTime || "";

    const notes =
        record.dataset.notes || "";


    /* Time */

    if (recordTime && startTime) {

        recordTime.value =
            startTime.substring(11, 16);

    }


    /* Diaper Type */

    const diaperRadio =
        document.querySelector(
            'input[name="diaper_type"][value="' +
            detailsText.trim() +
            '"]'
        );

    if (diaperRadio) {

        diaperRadio.checked = true;

    }

}


       
      /* -----------------------------------------
   NOTE
----------------------------------------- */

if (type === "note") {

    const startTime =
        record.dataset.startTime || "";

    const notes =
        record.dataset.notes || "";


    /* Time */

    if (recordTime && startTime) {

        recordTime.value =
            startTime.substring(11, 16);

    }


    /* Note Text */

    const noteText =
        document.getElementById("noteText");

    if (noteText) {

        noteText.value =
            detailsText.trim();

    }


    /* Additional Notes */

    const recordNotes =
        document.getElementById("recordNotes");

    if (recordNotes) {

        recordNotes.value =
            notes;

    }

}


        /* Close menu */
        const menu =
            record.querySelector(
                ".daily-record-dropdown"
            );


        if (menu) {

            menu.classList.remove(
                "show"
            );

        }


        /* Open modal */
        if (recordModalElement) {

            const modal =
                bootstrap.Modal.getOrCreateInstance(
                    recordModalElement
                );


            modal.show();

        }

    }


    /* =====================================================
       DELETE RECORD
    ===================================================== */

    function deleteRecord(record) {

    const confirmed =
        confirm(
            "Are you sure you want to delete this record?"
        );

    if (!confirmed) {
        return;
    }

    const recordId = record.dataset.id;

    const formData = new FormData();

    formData.append("record_id", recordId);

    formData.append(
        "baby_id",
        recordForm.querySelector('input[name="baby_id"]').value
    );

    fetch("../php/tracker/delete_record.php", {
        method: "POST",
        body: formData
    })
    .then(response => {

        if (response.ok) {

            window.location.reload();

        } else {

            alert("Failed to delete record.");

        }

    })
    .catch(error => {

        console.error(error);

        alert("Something went wrong.");

    });
}

    /* =====================================================
       SAVE / UPDATE RECORD
    ===================================================== */

    if (recordForm) {

        recordForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                const type =
                    recordType.value;


                if (!type) {

                    alert(
                        "Please select a record type."
                    );

                    return;

                }


                let timeText =
                    "";

                let detailsText =
                    "";


                /* -----------------------------------------
                   SLEEP
                ----------------------------------------- */

                if (type === "sleep") {

                    const sleepStart =
                        document.getElementById(
                            "sleepStart"
                        ).value;

                    const sleepEnd =
                        document.getElementById(
                            "sleepEnd"
                        ).value;


                    if (
                        !sleepStart ||
                        !sleepEnd
                    ) {

                        alert(
                            "Please enter sleep start and end time."
                        );

                        return;

                    }


                    timeText =
                        formatInputTime(
                            sleepStart
                        );


                    detailsText =
                        calculateSleepDuration(
                            sleepStart,
                            sleepEnd
                        );

                }


                /* -----------------------------------------
                   FEEDING
                ----------------------------------------- */

                if (type === "feeding") {

                    const feedingType =
                        document.getElementById(
                            "feedingType"
                        ).value;


                    const quantity =
                        document.getElementById(
                            "feedingQuantity"
                        ).value.trim();

                    const foodId =
                        document.getElementById("foodId").value;


                    if (!recordTime.value) {

                        alert(
                            "Please enter the feeding time."
                        );

                        return;

                    }


                    if (!feedingType) {

                        alert(
                            "Please select the feeding type."
                        );

                        return;

                    }


                    timeText =
                        formatInputTime(
                            recordTime.value
                        );


                    detailsText =
                        feedingType;


                    if (quantity) {

                        detailsText +=
                            " - " +
                            quantity;

                    }

                }


                /* -----------------------------------------
                   DIAPER
                ----------------------------------------- */

                if (type === "diaper") {

                    const selectedDiaper =
                        document.querySelector(
                            'input[name="diaper_type"]:checked'
                        );


                    if (!recordTime.value) {

                        alert(
                            "Please enter the diaper time."
                        );

                        return;

                    }


                    if (!selectedDiaper) {

                        alert(
                            "Please select the diaper type."
                        );

                        return;

                    }


                    timeText =
                        formatInputTime(
                            recordTime.value
                        );


                    detailsText =
                        selectedDiaper.value;

                }


                /* -----------------------------------------
                   NOTE
                ----------------------------------------- */

                if (type === "note") {

                    const noteText =
                        document.getElementById(
                            "noteText"
                        ).value.trim();


                    if (!recordTime.value) {

                        alert(
                            "Please enter the note time."
                        );

                        return;

                    }


                    if (!noteText) {

                        alert(
                            "Please write a note."
                        );

                        return;

                    }


                    timeText =
                        formatInputTime(
                            recordTime.value
                        );


                    detailsText =
                        noteText;

                }


                /* -----------------------------------------
                   UPDATE EXISTING
                ----------------------------------------- */

                if (editingRecord) {

                    const recordId = editingRecord.dataset.id;

                    const formData = new FormData(recordForm);

                    formData.append("record_id", recordId);

                    fetch("../php/tracker/update_record.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                if (response.ok) {
                   window.location.reload();
                } else {
                     alert("Failed to update record.");
                }
            })
            .catch(error => {
            console.error(error);
            alert("Something went wrong.");
         });

        } else {

                recordForm.submit();

        }


                updateRecordsCount();


                /* Close modal */
                if (recordModalElement) {

                    const modal =
                        bootstrap.Modal.getOrCreateInstance(
                            recordModalElement
                        );


                    modal.hide();

                }


                resetRecordForm();

            }
        );

    }


    /* =====================================================
       CREATE RECORD
    ===================================================== */

    function createRecord(
        type,
        timeText,
        detailsText
    ) {

        if (!recordsList) {
            return;
        }


        const record =
            document.createElement(
                "article"
            );


        record.className =
            "daily-record-item";


        record.dataset.type =
            type;


        record.dataset.search =
            (
                type +
                " " +
                timeText +
                " " +
                detailsText
            ).toLowerCase();


        /* Icon */
        const iconWrapper =
            document.createElement(
                "div"
            );


        iconWrapper.className =
            "daily-record-icon " +
            getIconClass(type);


        iconWrapper.innerHTML =
            `<i class="bi ${getIcon(type)}"></i>`;


        /* Time */
        const time =
            document.createElement(
                "div"
            );


        time.className =
            "daily-record-time";


        time.textContent =
            timeText;


        /* Details */
        const details =
            document.createElement(
                "div"
            );


        details.className =
            "daily-record-details";


        const title =
            document.createElement(
                "strong"
            );


        title.textContent =
            getRecordTitle(type);


        const description =
            document.createElement(
                "p"
            );


        description.textContent =
            detailsText;


        details.appendChild(title);
        details.appendChild(description);


        /* Menu */
        const menuWrapper =
            document.createElement(
                "div"
            );


        menuWrapper.className =
            "daily-record-menu";


        menuWrapper.innerHTML = `

            <button
                type="button"
                class="daily-more-btn"
                aria-label="More options"
            >
                <i class="bi bi-three-dots-vertical"></i>
            </button>

            <div class="daily-record-dropdown">

                <button
                    type="button"
                    class="daily-edit-record"
                >
                    <i class="bi bi-pencil"></i>
                    Edit
                </button>

                <button
                    type="button"
                    class="daily-delete-record"
                >
                    <i class="bi bi-trash"></i>
                    Delete
                </button>

            </div>

        `;


        record.appendChild(iconWrapper);
        record.appendChild(time);
        record.appendChild(details);
        record.appendChild(menuWrapper);


        /* Insert before empty state */
        if (emptyState) {

            recordsList.insertBefore(
                record,
                emptyState
            );

        } else {

            recordsList.appendChild(
                record
            );

        }


        /* Hide empty state */
        if (emptyState) {

            emptyState.style.display =
                "none";

        }

    }


    /* =====================================================
       UPDATE RECORD
    ===================================================== */

    function updateRecord(
        record,
        type,
        timeText,
        detailsText
    ) {

        record.dataset.type =
            type;


        record.dataset.search =
            (
                type +
                " " +
                timeText +
                " " +
                detailsText
            ).toLowerCase();


        /* Icon */
        const iconWrapper =
            record.querySelector(
                ".daily-record-icon"
            );


        if (iconWrapper) {

            iconWrapper.className =
                "daily-record-icon " +
                getIconClass(type);


            iconWrapper.innerHTML =
                `<i class="bi ${getIcon(type)}"></i>`;

        }


        /* Time */
        const time =
            record.querySelector(
                ".daily-record-time"
            );


        if (time) {

            time.textContent =
                timeText;

        }


        /* Title */
        const title =
            record.querySelector(
                ".daily-record-details strong"
            );


        if (title) {

            title.textContent =
                getRecordTitle(type);

        }


        /* Details */
        const details =
            record.querySelector(
                ".daily-record-details p"
            );


        if (details) {

            details.textContent =
                detailsText;

        }

    }


    /* =====================================================
       SEARCH
    ===================================================== */

    if (searchInput && recordsList) {

        searchInput.addEventListener(
            "input",
            function () {

                const query =
                    searchInput.value
                        .trim()
                        .toLowerCase();


                const records =
                    recordsList.querySelectorAll(
                        ".daily-record-item"
                    );


                let visibleCount =
                    0;


                records.forEach(
                    function (record) {

                        const searchData =
                            record.dataset.search ||
                            "";


                        const matches =
                            searchData.includes(
                                query
                            );


                        record.style.display =
                            matches
                                ? ""
                                : "grid";


                        if (!matches) {

                            record.style.display =
                                "none";

                        }


                        if (matches) {

                            visibleCount++;

                        }

                    }
                );


                updateRecordsCount(
                    visibleCount
                );

            }
        );

    }


    /* =====================================================
       UPDATE RECORD COUNT
    ===================================================== */

    function updateRecordsCount(
        customCount = null
    ) {

        if (!recordsList) {
            return;
        }


        const records =
            recordsList.querySelectorAll(
                ".daily-record-item"
            );


        let count =
            customCount;


        if (count === null) {

            count = 0;


            records.forEach(
                function (record) {

                    if (
                        record.style.display !==
                        "none"
                    ) {

                        count++;

                    }

                }
            );

        }


        if (recordsCount) {

            recordsCount.textContent =
                count +
                (
                    count === 1
                        ? " Record"
                        : " Records"
                );

        }


        if (emptyState) {

            emptyState.style.display =
                count === 0
                    ? "block"
                    : "none";

        }

    }


    /* =====================================================
       ICONS
    ===================================================== */

    function getIcon(type) {

        const icons = {

            sleep:
                "bi-moon-stars",

            feeding:
                "bi-cup-straw",

            diaper:
                "bi-droplet",

            note:
                "bi-journal-text"

        };


        return (
            icons[type] ||
            "bi-calendar-check"
        );

    }


    /* =====================================================
       ICON CLASSES
    ===================================================== */

    function getIconClass(type) {

        const classes = {

            sleep:
                "sleep-record-icon",

            feeding:
                "feeding-record-icon",

            diaper:
                "diaper-record-icon",

            note:
                "note-record-icon"

        };


        return (
            classes[type] ||
            "note-record-icon"
        );

    }


    /* =====================================================
       RECORD TITLES
    ===================================================== */

    function getRecordTitle(type) {

        const titles = {

            sleep:
                "Sleep",

            feeding:
                "Feeding",

            diaper:
                "Diaper",

            note:
                "Note"

        };


        return (
            titles[type] ||
            "Record"
        );

    }


    /* =====================================================
       FORMAT TIME
    ===================================================== */

    function formatInputTime(time) {

        if (!time) {

            return "08:00 AM";

        }


        const parts =
            time.split(":");


        let hours =
            parseInt(
                parts[0],
                10
            );


        const minutes =
            parts[1];


        const period =
            hours >= 12
                ? "PM"
                : "AM";


        hours =
            hours % 12 || 12;


        return (
            String(hours).padStart(
                2,
                "0"
            ) +
            ":" +
            minutes +
            " " +
            period
        );

    }


    /* =====================================================
       CONVERT DISPLAY TIME TO INPUT TIME
    ===================================================== */

    function convertToInputTime(
        time
    ) {

        if (!time) {
            return "";
        }


        const match =
            time.match(
                /(\d{1,2}):(\d{2})\s*(AM|PM)/i
            );


        if (!match) {
            return "";
        }


        let hours =
            parseInt(
                match[1],
                10
            );


        const minutes =
            match[2];


        const period =
            match[3].toUpperCase();


        if (
            period === "PM" &&
            hours !== 12
        ) {

            hours += 12;

        }


        if (
            period === "AM" &&
            hours === 12
        ) {

            hours = 0;

        }


        return (
            String(hours).padStart(
                2,
                "0"
            ) +
            ":" +
            minutes
        );

    }


    /* =====================================================
       CONVERT TIME TO MINUTES
    ===================================================== */

    function convertTimeToMinutes(
        time
    ) {

        if (!time) {
            return 0;
        }


        const parts =
            time.split(":");


        return (
            parseInt(parts[0], 10) *
            60 +
            parseInt(parts[1], 10)
        );

    }


    /* =====================================================
       MINUTES TO INPUT TIME
    ===================================================== */

    function minutesToTimeInput(
        totalMinutes
    ) {

        totalMinutes =
            totalMinutes %
            (24 * 60);


        let hours =
            Math.floor(
                totalMinutes / 60
            );


        const minutes =
            totalMinutes % 60;


        return (
            String(hours).padStart(
                2,
                "0"
            ) +
            ":" +
            String(minutes).padStart(
                2,
                "0"
            )
        );

    }


    /* =====================================================
       CALCULATE SLEEP DURATION
    ===================================================== */

    function calculateSleepDuration(
        start,
        end
    ) {

        let startMinutes =
            convertTimeToMinutes(
                start
            );


        let endMinutes =
            convertTimeToMinutes(
                end
            );


        /* Sleep can continue after midnight */
        if (
            endMinutes <
            startMinutes
        ) {

            endMinutes +=
                24 * 60;

        }


        const difference =
            endMinutes -
            startMinutes;


        const hours =
            Math.floor(
                difference / 60
            );


        const minutes =
            difference % 60;


        let result =
            "Slept for ";


        if (hours > 0) {

            result +=
                hours +
                (
                    hours === 1
                        ? " hour"
                        : " hours"
                );

        }


        if (minutes > 0) {

            if (hours > 0) {

                result += " ";

            }


            result +=
                minutes +
                " minutes";

        }


        if (
            hours === 0 &&
            minutes === 0
        ) {

            result +=
                "0 minutes";

        }


        return result;

    }


    /* =====================================================
       EXTRACT SLEEP DURATION
    ===================================================== */

    function extractSleepDuration(
        text
    ) {

        if (!text) {
            return 0;
        }


        let total =
            0;


        const hoursMatch =
            text.match(
                /(\d+)\s*hour/
            );


        const minutesMatch =
            text.match(
                /(\d+)\s*minute/
            );


        if (hoursMatch) {

            total +=
                parseInt(
                    hoursMatch[1],
                    10
                ) * 60;

        }


        if (minutesMatch) {

            total +=
                parseInt(
                    minutesMatch[1],
                    10
                );

        }


        return total;

    }


    /* =====================================================
       INITIALIZE
    ===================================================== */

    updateRecordFields();

    updateRecordsCount();

});






/* ================================================================
   SETTINGS PAGE
================================================================ */

document.addEventListener("DOMContentLoaded", function () {


    /* =========================================================
       1. EDIT ACCOUNT INFORMATION
    ========================================================= */

    const editAccountBtn =
        document.getElementById("editAccountBtn");

    const cancelAccountBtn =
        document.getElementById("cancelAccountBtn");

    const accountForm =
        document.getElementById("accountForm");

    const accountFormActions =
        document.getElementById("accountFormActions");

    const accountInputs =
        document.querySelectorAll(
            "#accountForm input"
        );


    if (
        editAccountBtn &&
        accountForm
    ) {

        editAccountBtn.addEventListener(
            "click",
            function () {

                accountInputs.forEach(
                    function (input) {

                        input.disabled = false;

                    }
                );


                accountFormActions.classList.add(
                    "show"
                );


                editAccountBtn.style.display =
                    "none";


                accountInputs[0].focus();

            }
        );

    }


    /* =========================================================
       2. CANCEL ACCOUNT EDIT
    ========================================================= */

    if (cancelAccountBtn) {

        cancelAccountBtn.addEventListener(
            "click",
            function () {

                accountInputs.forEach(
                    function (input) {

                        input.disabled = true;

                    }
                );


                accountFormActions.classList.remove(
                    "show"
                );


                if (editAccountBtn) {

                    editAccountBtn.style.display =
                        "inline-flex";

                }

            }
        );

    }


    /* =========================================================
       3. SAVE ACCOUNT INFORMATION - FRONTEND DEMO
    ========================================================= */

    if (accountForm) {

        accountForm.addEventListener(
            "submit",
            function (event) {

                event.preventDefault();


                accountInputs.forEach(
                    function (input) {

                        input.disabled = true;

                    }
                );


                accountFormActions.classList.remove(
                    "show"
                );


                if (editAccountBtn) {

                    editAccountBtn.style.display =
                        "inline-flex";

                }


                alert(
                    "Your account information has been updated."
                );

            }
        );

    }


    /* =========================================================
       4. NOTIFICATION SWITCHES
    ========================================================= */

    const notificationSwitches =
        document.querySelectorAll(
            ".settings-switch input"
        );


    notificationSwitches.forEach(
        function (toggle) {

            toggle.addEventListener(
                "change",
                function () {

                    const settingName =
                        toggle.id;

                    const status =
                        toggle.checked
                            ? "enabled"
                            : "disabled";


                    console.log(
                        settingName +
                        " " +
                        status
                    );

                }
            );

        }
    );


    /* =========================================================
       5. PASSWORD VISIBILITY
    ========================================================= */

    const passwordToggles =
        document.querySelectorAll(
            ".password-toggle"
        );


    passwordToggles.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    const targetId =
                        button.dataset.target;

                    const input =
                        document.getElementById(
                            targetId
                        );

                    const icon =
                        button.querySelector("i");


                    if (!input) {
                        return;
                    }


                    if (
                        input.type === "password"
                    ) {

                        input.type = "text";

                        icon.classList.remove(
                            "bi-eye"
                        );

                        icon.classList.add(
                            "bi-eye-slash"
                        );

                    } else {

                        input.type = "password";

                        icon.classList.remove(
                            "bi-eye-slash"
                        );

                        icon.classList.add(
                            "bi-eye"
                        );

                    }

                }
            );

        }
    );


/* =========================================================
   6. CHANGE PASSWORD
========================================================= */

const changePasswordForm =
    document.getElementById(
        "changePasswordForm"
    );

const passwordError =
    document.getElementById(
        "passwordError"
    );


if (changePasswordForm) {

    changePasswordForm.addEventListener(
        "submit",
        function (event) {

            const currentPassword =
                document.getElementById(
                    "currentPassword"
                ).value.trim();

            const newPassword =
                document.getElementById(
                    "newPassword"
                ).value;

            const confirmPassword =
                document.getElementById(
                    "confirmPassword"
                ).value;


            if (!currentPassword) {

                event.preventDefault();

                passwordError.textContent =
                    "Please enter your current password.";

                return;

            }


            if (newPassword.length < 8) {

                event.preventDefault();

                passwordError.textContent =
                    "New password must be at least 8 characters.";

                return;

            }


            if (newPassword !== confirmPassword) {

                event.preventDefault();

                passwordError.textContent =
                    "New passwords do not match.";

                return;

            }


            passwordError.textContent = "";

            // If everything is valid,
            // allow the form to submit to PHP.

        }
    );

}


    /* =========================================================
       7. CLEAR PASSWORD ERROR
    ========================================================= */

    const passwordInputs =
        document.querySelectorAll(
            ".password-input input"
        );


    passwordInputs.forEach(
        function (input) {

            input.addEventListener(
                "input",
                function () {

                    if (passwordError) {

                        passwordError.textContent =
                            "";

                    }

                }
            );

        }
    );


});



// profile image //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/* =========================================================
   PROFILE PAGE
========================================================= */


/* =========================================================
   PHOTO PICKER
========================================================= */

function openPhotoPicker() {

    const input = document.getElementById("momPhoto");

    if (!input) return;

    input.click();

    input.onchange = async function () {

        const file = input.files[0];

        if (!file) return;

        // Check file type
        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!allowedTypes.includes(file.type)) {
            alert("Please select a JPG, PNG, or WEBP image.");
            input.value = "";
            return;
        }

        // Check file size (5 MB)
        if (file.size > 5 * 1024 * 1024) {
            alert("Image size must be less than 5 MB.");
            input.value = "";
            return;
        }

        // Show preview immediately
        const image = document.getElementById("momImage");

        if (image) {
            image.src = URL.createObjectURL(file);
        }

        // Prepare image for PHP
        const formData = new FormData();
        formData.append("profile_image", file);

        try {

            const response = await fetch(
                "../php/profile/upload_profile_image.php",
                {
                    method: "POST",
                    body: formData
                }
            );

            const result = await response.json();

            if (!result.success) {
                alert(result.message || "Failed to upload image.");
                return;
            }

            // Use the saved image from server
            if (image && result.image) {
                image.src = result.image + "?t=" + Date.now();
            }

            alert(result.message || "Profile image updated successfully.");

        } catch (error) {

            console.error(error);
            alert("Something went wrong while uploading the image.");

        }

    };

}


document.addEventListener("DOMContentLoaded", function () {

    const photoInput = document.getElementById("momPhoto");
    const profileImage = document.getElementById("momImage");

    if (photoInput && profileImage) {

        photoInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            if (!file.type.match("image.*")) {

                alert("Please select an image.");

                this.value = "";

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                profileImage.src = event.target.result;

            };

            reader.readAsDataURL(file);

        });

    }

});


/* =========================================================
   OPEN / CLOSE EDIT PROFILE
========================================================= */

function openEditProfile() {

    const modal = document.getElementById("editProfileModal");

    if (!modal) {
        return;
    }

    // Open modal
    modal.classList.add("show");

    document.body.style.overflow = "hidden";


    // Fill existing profile data
    const name = document.getElementById("profileName");
    const email = document.getElementById("profileEmail");
    const phone = document.getElementById("profilePhone");
    const birthDate = document.getElementById("profileBirthDate");
    const gender = document.getElementById("profileGender");
    const language = document.getElementById("profileLanguage");
    const address = document.getElementById("profileAddress");
    const timezone = document.getElementById("profileTimezone");
    const about = document.getElementById("profileAbout");


    if (document.getElementById("editName")) {
        document.getElementById("editName").value =
            name?.textContent.trim() || "";
    }


    if (document.getElementById("editEmail")) {
        document.getElementById("editEmail").value =
            email?.textContent.trim() || "";
    }


    if (document.getElementById("editPhone")) {
        document.getElementById("editPhone").value =
            phone?.textContent.trim() || "";
    }


    if (document.getElementById("editBirthDate")) {
        document.getElementById("editBirthDate").value =
            birthDate?.dataset.value || "";
    }


    if (document.getElementById("editGender")) {
        document.getElementById("editGender").value =
            gender?.dataset.value || "";
    }


    if (document.getElementById("editLanguage")) {
        document.getElementById("editLanguage").value =
            language?.dataset.value || "English";
    }


    if (document.getElementById("editAddress")) {
        document.getElementById("editAddress").value =
            address?.textContent.trim() || "";
    }


    if (document.getElementById("editTimezone")) {
        document.getElementById("editTimezone").value =
            timezone?.textContent.trim() || "";
    }


    if (document.getElementById("editAbout")) {
        document.getElementById("editAbout").value =
            about?.textContent.trim() || "";
    }

}



function closeEditProfile() {

    const modal = document.getElementById("editProfileModal");

    if (!modal) {
        return;
    }

    modal.classList.remove("show");

    document.body.style.overflow = "";

}

/* =========================================================
   SECURITY MODALS
========================================================= */

function openChangePassword() {

    const modal = document.getElementById("changePasswordModal");

    if (modal) {

        modal.classList.add("show");

        document.body.style.overflow = "hidden";

    }

}


function openTwoFactor() {

    const modal = document.getElementById("twoFactorModal");

    if (modal) {

        modal.classList.add("show");

        document.body.style.overflow = "hidden";

    }

}


function openLoginActivity() {

    const modal = document.getElementById("loginActivityModal");

    if (modal) {

        modal.classList.add("show");

        document.body.style.overflow = "hidden";

    }

}


function openManageDevices() {

    const modal = document.getElementById("manageDevicesModal");

    if (modal) {

        modal.classList.add("show");

        document.body.style.overflow = "hidden";

    }

}


function closeSecurityBox(id) {

    const modal = document.getElementById(id);

    if (modal) {

        modal.classList.remove("show");

        document.body.style.overflow = "";

    }

}


/* =========================================================
   API HELPER
========================================================= */

async function profilePostJSON(url, data) {

    const response = await fetch(url, {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },

        body: JSON.stringify(data)

    });


    let result;

    try {

        result = await response.json();

    } catch (error) {

        throw new Error(
            "Unexpected response from server."
        );

    }


    if (!response.ok || !result.success) {

        throw new Error(
            result.message || "Something went wrong."
        );

    }


    return result;

}


/* =========================================================
   SAVE PROFILE
========================================================= */

async function saveEditProfile() {

    const payload = {

        name:
            document.getElementById("editName").value.trim(),

        email:
            document.getElementById("editEmail").value.trim(),

        phone:
            document.getElementById("editPhone").value.trim(),

        birth_date:
            document.getElementById("editBirthDate").value,

        gender:
            document.getElementById("editGender").value,

        address:
            document.getElementById("editAddress").value.trim(),

        language:
            document.getElementById("editLanguage").value,

        timezone:
            document.getElementById("editTimezone").value,

        about:
            document.getElementById("editAbout").value.trim()

    };


    /* Validation */

    if (!payload.name) {

        alert("Please enter your name.");

        return;

    }


    if (!payload.email) {

        alert("Please enter your email.");

        return;

    }


    try {

        const result = await profilePostJSON(
            "../php/profile/update_profile.php",
            payload
        );


        /* Update Personal Information */

        const valueName =
            document.getElementById("valueName");

        const valueEmail =
            document.getElementById("valueEmail");

        const valuePhone =
            document.getElementById("valuePhone");

        const valueBirth =
            document.getElementById("valueBirth");

        const valueGender =
            document.getElementById("valueGender");

        const valueAddress =
            document.getElementById("valueAddress");

        const valueLanguage =
            document.getElementById("valueLanguage");

        const valueTimezone =
            document.getElementById("valueTimezone");

        const valueAbout =
            document.getElementById("valueAbout");


        if (valueName) {
            valueName.textContent = payload.name;
        }

        if (valueEmail) {
            valueEmail.textContent = payload.email;
        }

        if (valuePhone) {
            valuePhone.textContent = payload.phone;
        }

        if (valueBirth) {
            valueBirth.textContent = payload.birth_date;
        }

        if (valueGender) {
            valueGender.textContent = payload.gender;
        }

        if (valueAddress) {
            valueAddress.textContent = payload.address;
        }

        if (valueLanguage) {
            valueLanguage.textContent = payload.language;
        }

        if (valueTimezone) {
            valueTimezone.textContent = payload.timezone;
        }

        if (valueAbout) {
            valueAbout.textContent = payload.about;
        }


        /* Update Profile Header */

        const profileName =
            document.getElementById("profileName");

        const profileEmail =
            document.getElementById("profileEmail");

        const profilePhone =
            document.getElementById("profilePhone");


        if (profileName) {
            profileName.textContent = payload.name;
        }

        if (profileEmail) {
            profileEmail.textContent = payload.email;
        }

        if (profilePhone) {
            profilePhone.textContent = payload.phone;
        }


        /* Update top user */

        const headerName =
            document.querySelector(".user-info strong");

        const dropdownName =
            document.querySelector(".user-dropdown-name strong");

        const dropdownEmail =
            document.querySelector(".user-dropdown-name span");


        if (headerName) {
            headerName.textContent = payload.name;
        }

        if (dropdownName) {
            dropdownName.textContent = payload.name;
        }

        if (dropdownEmail) {
            dropdownEmail.textContent = payload.email;
        }


        /* Close modal */

        closeEditProfile();


        alert(
            result.message ||
            "Profile updated successfully."
        );


    } catch (error) {

        console.error("Profile update error:", error);

        alert(
            error.message ||
            "Failed to update profile."
        );

    }

}
/* =========================================================
   CHANGE PASSWORD
========================================================= */

async function savePassword() {

    const currentPassword =
        document.getElementById("currentPassword").value;

    const newPassword =
        document.getElementById("newPassword").value;

    const confirmPassword =
        document.getElementById("confirmPassword").value;


    if (!currentPassword) {

        alert("Please enter your current password.");

        return;

    }


    if (newPassword.length < 8) {

        alert(
            "New password must be at least 8 characters."
        );

        return;

    }


    if (newPassword !== confirmPassword) {

        alert(
            "New passwords do not match."
        );

        return;

    }


    try {

        const result = await profilePostJSON(
            "../php/profile/update_password.php",
            {
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            }
        );


        document.getElementById(
            "currentPassword"
        ).value = "";

        document.getElementById(
            "newPassword"
        ).value = "";

        document.getElementById(
            "confirmPassword"
        ).value = "";


        closeSecurityBox(
            "changePasswordModal"
        );


        alert(
            result.message ||
            "Password changed successfully."
        );


    } catch (error) {

        alert(error.message);

    }

}


/* =========================================================
   TWO FACTOR
========================================================= */

async function saveTwoFactor() {

    const switchElement =
        document.getElementById("twoFactorSwitch");

    const enabled =
        switchElement.checked;


    try {

        const result = await profilePostJSON(
            "../php/profile/update_two_factor.php",
            {
                enabled: enabled
            }
        );


        const badge =
            document.getElementById("twoFactorBadge");

        const status =
            document.getElementById("twoFactorStatus");


        if (badge) {

            badge.style.display =
                result.enabled ? "" : "none";

        }


        if (status) {

            status.textContent =
                "Two-Factor Authentication is " +
                (
                    result.enabled
                        ? "Enabled"
                        : "Disabled"
                );

        }


        closeSecurityBox(
            "twoFactorModal"
        );


    } catch (error) {

        switchElement.checked = !enabled;

        alert(error.message);

    }

}


/* =========================================================
   PREFERENCES
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const toggles =
            document.querySelectorAll(
                ".preference-toggle"
            );


        toggles.forEach(function (toggle) {

            toggle.addEventListener(
                "change",
                async function () {

                    const key =
                        toggle.dataset.key;

                    const enabled =
                        toggle.checked;


                    try {

                        await profilePostJSON(
                            "../php/profile/update_preferences.php",
                            {
                                key: key,
                                enabled: enabled
                            }
                        );


                    } catch (error) {

                        toggle.checked =
                            !enabled;

                        alert(error.message);

                    }

                }
            );

        });

    }
);


/* =========================================================
   REMOVE DEVICE
========================================================= */

function removeDevice(button) {

    if (!button) {
        return;
    }


    const confirmed =
        confirm(
            "Are you sure you want to remove this device?"
        );


    if (!confirmed) {
        return;
    }


    const device =
        button.closest(".device-item");


    if (device) {

        device.remove();

    }

}


/* =========================================================
   CLOSE MODAL WHEN CLICKING OUTSIDE
========================================================= */

document.addEventListener(
    "click",
    function (event) {

        if (
            event.target.classList.contains(
                "profile-modal"
            )
        ) {

            event.target.classList.remove(
                "show"
            );

            document.body.style.overflow = "";

        }

    }
);
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("globalSearch");

    if (!searchInput) return;

    searchInput.addEventListener("focus", function () {
        this.removeAttribute("readonly");
    });

});

document.addEventListener("DOMContentLoaded", async function () {

    const avatar = document.querySelector(".user-avatar");

    if (!avatar) return;

    try {

        const response = await fetch("../php/profile/get_profile.php");

        const result = await response.json();

        if (result.success && result.user && result.user.profile_image) {

            avatar.innerHTML = `
                <img
                    src="../${result.user.profile_image}?t=${Date.now()}"
                    alt="Profile"
                >
            `;

        }

    } catch (error) {

        console.error("Failed to load profile image:", error);

    }

});


// ===========================================================
//                BABY PROFILE PAGE
// ===========================================================

document.addEventListener("DOMContentLoaded", function () {

    const babiesList = document.getElementById("babiesList");

    if (!babiesList) return;

        /* =====================================================
       ELEMENTS
    ===================================================== */

    const boy = document.getElementById("boy");
    const girl = document.getElementById("girl");

    const saveBabyBtn = document.getElementById("save");

    const addTab = document.getElementById("addTab");
    const babiesTab = document.getElementById("babiesTab");

    const addSection = document.getElementById("addSection");
    const babiesSection = document.getElementById("babiesSection");


    /* =====================================================
       GENDER
    ===================================================== */

    if (boy && girl) {

        boy.addEventListener("click", function () {

            boy.classList.add("selected");
            girl.classList.remove("selected");

        });


        girl.addEventListener("click", function () {

            girl.classList.add("selected");
            boy.classList.remove("selected");

        });

    }


    /* =====================================================
       TABS
    ===================================================== */

    if (addTab && babiesTab && addSection && babiesSection) {

        addTab.addEventListener("click", function () {

            addTab.classList.add("active");
            babiesTab.classList.remove("active");

            addSection.hidden = false;
            babiesSection.hidden = true;

            window.history.replaceState(
                null,
                "",
                window.location.pathname
            );

        });


        babiesTab.addEventListener("click", function () {

            addTab.classList.remove("active");
            babiesTab.classList.add("active");

            addSection.hidden = true;
            babiesSection.hidden = false;

            window.history.replaceState(
                null,
                "",
                "#my-babies"
            );

        });


        if (window.location.hash === "#my-babies") {

            addTab.classList.remove("active");
            babiesTab.classList.add("active");

            addSection.hidden = true;
            babiesSection.hidden = false;

        }

    }


    /* =====================================================
       SAVE BABY
    ===================================================== */

    if (saveBabyBtn) {

        saveBabyBtn.addEventListener("click", async function () {

            const name =
                document.getElementById("name")?.value.trim();

            const birthDate =
                document.getElementById("date")?.value;

            const weight =
                document.getElementById("weight")?.value;

            const height =
                document.getElementById("height")?.value;

            const notes =
                document.getElementById("notes")?.value.trim();


            let gender = "";


            if (boy?.classList.contains("selected")) {
                gender = "Boy";
            }

            if (girl?.classList.contains("selected")) {
                gender = "Girl";
            }


            /* Validation */

            if (!name) {

                alert("Please enter baby's name.");

                return;
            }


            if (!birthDate) {

                alert("Please enter baby's date of birth.");

                return;
            }


            if (!gender) {

                alert("Please select baby's gender.");

                return;
            }


            const formData = new FormData();

            formData.append("name", name);
            formData.append("birth_date", birthDate);
            formData.append("gender", gender);
            formData.append("birth_weight", weight || "");
            formData.append("birth_height", height || "");
            formData.append("notes", notes || "");


            try {

                saveBabyBtn.disabled = true;

                saveBabyBtn.textContent = "Saving...";


                const response = await fetch(
                    "../php/baby/add_baby.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );


                const data = await response.json();


                if (!data.success) {

                    alert(
                        data.message ||
                        "Failed to add baby."
                    );

                    return;
                }


                alert("Baby added successfully ❤️");


                /* Clear form */

                document.getElementById("name").value = "";
                document.getElementById("date").value = "";
                document.getElementById("weight").value = "";
                document.getElementById("height").value = "";
                document.getElementById("notes").value = "";


                boy?.classList.remove("selected");
                girl?.classList.remove("selected");


                /* Open My Babies */

                window.location.hash = "my-babies";

                window.location.reload();


            } catch (error) {

                console.error(
                    "Add baby error:",
                    error
                );


                alert(
                    "Something went wrong. Please try again."
                );


            } finally {

                saveBabyBtn.disabled = false;

                saveBabyBtn.innerHTML =
                    '<i class="fa-regular fa-floppy-disk"></i> Save Baby';

            }

        });

    }
    /* =====================================================
       BABY MORE MENU
    ===================================================== */

    function closeAllBabyMenus() {
        document.querySelectorAll(".baby-actions-menu").forEach(function (menu) {
            menu.remove();
        });
    }


    function createBabyMenu(card, babyId) {

        closeAllBabyMenus();

        const menu = document.createElement("div");

        menu.className = "baby-actions-menu";

        menu.innerHTML = `
            <button type="button" class="baby-edit-action">
                <i class="bi bi-pencil"></i>
                Edit
            </button>

            <button type="button" class="baby-delete-action">
                <i class="bi bi-trash"></i>
                Delete
            </button>
        `;

        card.appendChild(menu);


        /* EDIT */

        menu.querySelector(".baby-edit-action").addEventListener(
            "click",
            function () {

                closeAllBabyMenus();

                openEditBabyModal(card);
            }
        );


        /* DELETE */

        menu.querySelector(".baby-delete-action").addEventListener(
            "click",
            async function () {

                closeAllBabyMenus();

                await deleteBaby(card, babyId);
            }
        );
    }


    /* =====================================================
       CLICK THREE DOTS
    ===================================================== */

    babiesList.addEventListener("click", function (event) {

        const moreButton =
            event.target.closest(".baby-more-btn");

        if (!moreButton) return;


        const card =
            moreButton.closest(".saved-baby-card");

        if (!card) return;


        const babyId =
            card.dataset.babyId;


        createBabyMenu(card, babyId);
    });


    /* =====================================================
       CLOSE MENU WHEN CLICK OUTSIDE
    ===================================================== */

    document.addEventListener("click", function (event) {

        if (
            !event.target.closest(".baby-more-btn") &&
            !event.target.closest(".baby-actions-menu")
        ) {
            closeAllBabyMenus();
        }

    });


    /* =====================================================
       EDIT BABY MODAL
    ===================================================== */

    function createEditModal() {

        if (document.getElementById("editBabyModal")) {
            return;
        }


        const modal = document.createElement("div");

        modal.id = "editBabyModal";

        modal.className = "baby-edit-modal";


        modal.innerHTML = `

            <div class="baby-edit-box">

                <div class="baby-edit-header">

                    <div>
                        <span class="section-label">
                            BABY PROFILE
                        </span>

                        <h2>
                            Edit Baby
                        </h2>
                    </div>

                    <button
                        type="button"
                        class="baby-edit-close"
                        id="closeEditBaby"
                    >
                        &times;
                    </button>

                </div>


                <div class="baby-edit-form">

                    <input
                        type="hidden"
                        id="editBabyId"
                    >


                    <div class="baby-edit-field">

                        <label>
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="editBabyName"
                        >

                    </div>


                    <div class="baby-edit-field">

                        <label>
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            id="editBabyDate"
                        >

                    </div>


                    <div class="baby-edit-field">

                        <label>
                            Gender
                        </label>

                        <div class="edit-gender-options">

                            <label>
                                <input
                                    type="radio"
                                    name="editBabyGender"
                                    value="Boy"
                                >
                                Boy
                            </label>

                            <label>
                                <input
                                    type="radio"
                                    name="editBabyGender"
                                    value="Girl"
                                >
                                Girl
                            </label>

                        </div>

                    </div>


                    <div class="baby-edit-row">

                        <div class="baby-edit-field">

                            <label>
                                Birth Weight (kg)
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                id="editBabyWeight"
                            >

                        </div>


                        <div class="baby-edit-field">

                            <label>
                                Birth Height (cm)
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                id="editBabyHeight"
                            >

                        </div>

                    </div>


                    <div class="baby-edit-field">

                        <label>
                            Notes
                        </label>

                        <textarea
                            id="editBabyNotes"
                            rows="4"
                        ></textarea>

                    </div>


                    <div class="baby-edit-buttons">

                        <button
                            type="button"
                            class="baby-cancel-btn"
                            id="cancelEditBaby"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="baby-save-edit-btn"
                            id="saveEditBaby"
                        >
                            Save Changes
                        </button>

                    </div>

                </div>

            </div>

        `;


        document.body.appendChild(modal);


        document
            .getElementById("closeEditBaby")
            .addEventListener(
                "click",
                closeEditBabyModal
            );


        document
            .getElementById("cancelEditBaby")
            .addEventListener(
                "click",
                closeEditBabyModal
            );


        document
            .getElementById("saveEditBaby")
            .addEventListener(
                "click",
                submitEditBaby
            );


        /* close when clicking outside */

        modal.addEventListener("click", function (event) {

            if (event.target === modal) {
                closeEditBabyModal();
            }

        });

    }


    /* =====================================================
       OPEN EDIT MODAL
    ===================================================== */

    function openEditBabyModal(card) {

        createEditModal();


        const babyId =
            card.dataset.babyId;

        const name =
            card.dataset.name || "";

        const birthDate =
            card.dataset.birthDate || "";

        const gender =
            card.dataset.gender || "";

        const weight =
            card.dataset.birthWeight || "";

        const height =
            card.dataset.birthHeight || "";

        const notes =
            card.dataset.notes || "";


        document.getElementById("editBabyId").value =
            babyId;

        document.getElementById("editBabyName").value =
            name;

        document.getElementById("editBabyDate").value =
            birthDate;

        document.getElementById("editBabyWeight").value =
            weight;

        document.getElementById("editBabyHeight").value =
            height;

        document.getElementById("editBabyNotes").value =
            notes;


        document
            .querySelectorAll(
                'input[name="editBabyGender"]'
            )
            .forEach(function (radio) {

                radio.checked =
                    radio.value === gender;

            });


        document
            .getElementById("editBabyModal")
            .classList.add("show");
    }


    /* =====================================================
       CLOSE EDIT MODAL
    ===================================================== */

    function closeEditBabyModal() {

        const modal =
            document.getElementById("editBabyModal");

        if (!modal) return;

        modal.classList.remove("show");
    }


    /* =====================================================
       SUBMIT EDIT
    ===================================================== */

    async function submitEditBaby() {

        const babyId =
            document.getElementById("editBabyId").value;

        const name =
            document.getElementById("editBabyName").value.trim();

        const birthDate =
            document.getElementById("editBabyDate").value;

        const weight =
            document.getElementById("editBabyWeight").value;

        const height =
            document.getElementById("editBabyHeight").value;

        const notes =
            document.getElementById("editBabyNotes").value.trim();


        let gender = "";


        document
            .querySelectorAll(
                'input[name="editBabyGender"]'
            )
            .forEach(function (radio) {

                if (radio.checked) {
                    gender = radio.value;
                }

            });


        /* Validation */

        if (!name) {

            alert("Please enter baby's name.");

            return;
        }


        if (!birthDate) {

            alert("Please select baby's birth date.");

            return;
        }


        if (!gender) {

            alert("Please select baby's gender.");

            return;
        }


        const formData =
            new FormData();


        formData.append(
            "id",
            babyId
        );

        formData.append(
            "name",
            name
        );

        formData.append(
            "birth_date",
            birthDate
        );

        formData.append(
            "gender",
            gender
        );

        formData.append(
            "birth_weight",
            weight
        );

        formData.append(
            "birth_height",
            height
        );

        formData.append(
            "notes",
            notes
        );


        const saveButton =
            document.getElementById("saveEditBaby");


        try {

            saveButton.disabled = true;

            saveButton.textContent =
                "Saving...";


            const response =
                await fetch(
                    "../php/baby/update_baby.php",
                    {
                        method: "POST",
                        body: formData
                    }
                );


            const data =
                await response.json();


            if (!data.success) {

                alert(
                    data.message ||
                    "Failed to update baby."
                );

                return;
            }


            alert(
                "Baby profile updated successfully ❤️"
            );


            closeEditBabyModal();


            /*
             Reload page so PHP gets
             the latest database values.
            */

            window.location.reload();


        } catch (error) {

            console.error(
                "Update baby error:",
                error
            );


            alert(
                "Something went wrong. Please try again."
            );


        } finally {

            saveButton.disabled = false;

            saveButton.textContent =
                "Save Changes";

        }

    }


    /* =====================================================
       DELETE BABY
    ===================================================== */

    async function deleteBaby(card, babyId) {

        const babyName =
            card.dataset.name || "this baby";


        const confirmed =
            confirm(
                `Are you sure you want to delete ${babyName}'s profile?`
            );


        if (!confirmed) return;


        try {

            const response =
                await fetch(
                    "../php/baby/delete_baby.php",
                    {
                        method: "POST",

                        headers: {
                            "Content-Type":
                                "application/json"
                        },

                        body: JSON.stringify({
                            id: babyId
                        })
                    }
                );


            const data =
                await response.json();


            if (!data.success) {

                alert(
                    data.message ||
                    "Unable to delete this baby."
                );

                return;
            }


            alert(
                "Baby profile deleted successfully."
            );


            /*
             Remove card immediately
            */

            card.remove();


            /*
             If there are no cards left,
             reload to show empty state.
            */

            const remainingCards =
                document.querySelectorAll(
                    ".saved-baby-card"
                );


            if (remainingCards.length === 0) {

                window.location.reload();

            }


        } catch (error) {

            console.error(
                "Delete baby error:",
                error
            );


            alert(
                "Something went wrong while deleting the baby."
            );

        }

    }

});


/* =========================================================
   GLOBAL DYNAMIC NOTIFICATIONS
   ========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const notificationWrappers =
        document.querySelectorAll(".notification-wrapper");

    // لو الصفحة مفيهاش notification
    if (!notificationWrappers.length) return;

    fetch("../php/notifications/get_notifications.php", {
        method: "GET",
        cache: "no-store"
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Failed to load notifications");
            }

            return response.json();
        })

        .then(data => {

            if (!data.success) {
                console.error(
                    data.message || "Could not load notifications"
                );
                return;
            }

            const notifications = data.notifications || [];
            const count = notifications.length;

            notificationWrappers.forEach(wrapper => {

                const countElement =
                    wrapper.querySelector(".notification-count");

                const menu =
                    wrapper.querySelector(".notification-menu");

                if (!menu) return;


                /* =========================
                   NOTIFICATION COUNT
                   ========================= */

                if (countElement) {

                    if (count > 0) {
                        countElement.textContent = count;
                        countElement.style.display = "inline-flex";
                    } else {
                        countElement.textContent = "";
                        countElement.style.display = "none";
                    }

                }


                /* =========================
                   NOTIFICATION MENU
                   ========================= */

                let html = `
                    <div class="notification-header">
                        <strong>Notifications</strong>
                    </div>
                `;


                // مفيش Notifications
                if (notifications.length === 0) {

                    html += `
                        <div class="notification-item">
                            <div class="notification-content">
                                <strong>No new notifications</strong>
                                <span>You're all caught up.</span>
                            </div>
                        </div>
                    `;

                }

                // فيه Notifications
                else {
notifications.forEach(notification => {

    html += `
        <a href="${notification.link || '#'}"
           class="notification-item dynamic-notification"
           data-notification-id="${notification.id || ''}"
           style="text-decoration:none;">

            <div class="notification-icon">
                <i class="${notification.icon || 'bi bi-bell'}"></i>
            </div>

            <div class="notification-content">

                <strong>
                    ${escapeNotificationHTML(notification.title)}
                </strong>

                <span>
                    ${escapeNotificationHTML(notification.message)}
                </span>

                ${
                    notification.time
                        ? `<small>${escapeNotificationHTML(notification.time)}</small>`
                        : ""
                }

            </div>

        </a>
    `;

});

                }


        menu.innerHTML = html;

        /* MARK NOTIFICATION AS SEEN */

        const notificationItems =
            menu.querySelectorAll(".dynamic-notification");

        notificationItems.forEach(function (item) {

            item.addEventListener("click", async function (event) {

                event.preventDefault();

                const notificationId =
                    this.dataset.notificationId;

                const notificationLink =
                    this.getAttribute("href");

                if (!notificationId) {
                    if (notificationLink) {
                        window.location.href = notificationLink;
                    }
                    return;
                }

                try {

                    await fetch(
                        "../php/notifications/mark_notification_seen.php",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type":
                                    "application/x-www-form-urlencoded"
                            },
                            body:
                                "notification_id=" +
                                encodeURIComponent(notificationId)
                        }
                    );

                    if (notificationLink) {
                        window.location.href = notificationLink;
                    }

                } catch (error) {

                    console.error(
                        "Failed to mark notification as seen:",
                        error
                    );

                    if (notificationLink) {
                        window.location.href = notificationLink;
                    }

                }

            });

        });

    });   // notificationWrappers.forEach

})       // fetch .then

.catch(error => {
    console.error("Notification error:", error);
});

});       // DOMContentLoaded


/* =========================================================
   ESCAPE NOTIFICATION TEXT
   ========================================================= */

function escapeNotificationHTML(text) {

    if (text === null || text === undefined) {
        return "";
    }

    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}