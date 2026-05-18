/* =========================
   UTIL HELPERS
========================= */

const $ = (id) => document.getElementById(id);

/* =========================
   LOAD ORGANIZATIONS
========================= */

async function loadOrganizations() {

    const orgGrid = $("orgGrid");
    if (!orgGrid) return;

    orgGrid.innerHTML = "Chargement...";

    try {
        const res = await fetch("/mindgrow/api/api_organizations.php");
        const orgs = await res.json();

        orgGrid.innerHTML = "";

        orgs.forEach(org => {
            orgGrid.innerHTML += `
                <div class="card">
                    <img src="/mindgrow/uploads/images/${org.image}" alt="${org.name}">
                    <h3>${org.name}</h3>

                    <button class="btn primary"
                        onclick="showCert(${org.id}, '${org.name}')">
                        Voir certifications
                    </button>
                </div>
            `;
        });

    } catch (e) {
        console.error(e);
        orgGrid.innerHTML = "Erreur chargement";
    }
}

/* =========================
   SHOW CERTIFICATIONS
========================= */

async function showCert(orgId, orgName) {

    const orgPage = $("orgPage");
    const certPage = $("certPage");

    if (orgPage) orgPage.classList.add("hidden");
    if (certPage) certPage.classList.remove("hidden");

    const certTitle = $("certTitle");
    if (certTitle) certTitle.innerText = orgName + " Certifications";

    const certGrid = $("certGrid");
    if (!certGrid) return;

    certGrid.innerHTML = "Chargement...";

    try {
        const res = await fetch("/mindgrow/api/api_certifications.php");
        const certifications = await res.json();

        const filtered = certifications.filter(c =>
            parseInt(c.org_id) === parseInt(orgId)
        );

        certGrid.innerHTML = "";

        if (filtered.length === 0) {
            certGrid.innerHTML = `<p>Aucune certification trouvée</p>`;
            return;
        }

        filtered.forEach(cert => {
            certGrid.innerHTML += `
                <div class="card">
                    <h3>${cert.name}</h3>

                    <button class="btn primary"
                        onclick="showCourse('${cert.name}')">
                        Voir cours
                    </button>
                </div>
            `;
        });

    } catch (e) {
        console.error(e);
        certGrid.innerHTML = "Erreur certifications";
    }
}

/* =========================
   SHOW COURSES
========================= */

async function showCourse(certName) {

    const certPage = $("certPage");
    const coursePage = $("coursePage");

    if (certPage) certPage.classList.add("hidden");
    if (coursePage) coursePage.classList.remove("hidden");

    const courseTitle = $("courseTitle");
    if (courseTitle) courseTitle.innerText = certName;

    const content = $("courseContent");
    if (!content) return;

    content.innerHTML = "Chargement...";

    try {
        const res = await fetch("/mindgrow/api/api_courses.php");
        const courses = await res.json();

        const filtered = courses.filter(c =>
            c.cert_name === certName
        );

        content.innerHTML = "";

        if (filtered.length === 0) {
            content.innerHTML = "<p>Aucun cours</p>";
            return;
        }

        filtered.forEach(course => {
            content.innerHTML += `
                <div class="card">
                    <h3>${course.title}</h3>

                    <a class="btn primary"
                       href="/mindgrow/${course.file_path}"
                       target="_blank">
                       Commencer
                    </a>
                </div>
            `;
        });

    } catch (e) {
        console.error(e);
        content.innerHTML = "Erreur cours";
    }
}

/* =========================
   BACK NAVIGATION
========================= */

function backToOrg() {
    const certPage = $("certPage");
    const orgPage = $("orgPage");

    if (certPage) certPage.classList.add("hidden");
    if (orgPage) orgPage.classList.remove("hidden");
}

function backToCert() {
    const coursePage = $("coursePage");
    const certPage = $("certPage");

    if (coursePage) coursePage.classList.add("hidden");
    if (certPage) certPage.classList.remove("hidden");
}

/* =========================
   INIT APP
========================= */

document.addEventListener("DOMContentLoaded", () => {
    loadOrganizations();
});
function toggleAccordion(id) {

    const el = document.getElementById(id);

    if (!el) return;

    if (getComputedStyle(el).display === "none") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

function enableAdd() {
    document.getElementById("certSelect").required = false;
    document.getElementById("nameField").required = true;
    document.getElementById("orgField").required = true;
}

function enableEdit() {
    document.getElementById("certSelect").required = true;
    document.getElementById("nameField").required = true;
    document.getElementById("orgField").required = true;
}

function enableDelete() {
    document.getElementById("certSelect").required = true;
    document.getElementById("nameField").required = false;
    document.getElementById("orgField").required = false;
}