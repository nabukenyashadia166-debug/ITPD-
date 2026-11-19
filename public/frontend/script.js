/*
 * Campus Lost & Found - shared page interactions.
 * This is a front-end demo: data is saved in this browser only. Real accounts,
 * uploads and shared records need a server and database.
 */
(function () {
    "use strict";

    const STORE = {
        items: "campusLostFound.items",
        claims: "campusLostFound.claims",
        profile: "campusLostFound.profile",
        photo: "campusLostFound.pendingPhoto"
    };

    const one = (selector, parent) => (parent || document).querySelector(selector);
    const all = (selector, parent) => Array.from((parent || document).querySelectorAll(selector));

    function readList(key) {
        try {
            const value = JSON.parse(localStorage.getItem(key) || "[]");
            return Array.isArray(value) ? value : [];
        } catch (error) {
            return [];
        }
    }

    function formMessage(form, message, type) {
        let note = one("[data-form-message]", form);
        if (!note) {
            note = document.createElement("div");
            note.dataset.formMessage = "true";
            note.className = "alert mt-3 mb-0";
            note.setAttribute("role", "status");
            form.appendChild(note);
        }
        note.className = "alert alert-" + (type || "info") + " mt-3 mb-0";
        note.textContent = message;
    }

    function safelySave(key, value, form) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (error) {
            if (form) formMessage(form, "Your browser could not save this information. Try a smaller photo or free up browser storage.", "warning");
            return false;
        }
    }

    function displayDate(value) {
        if (!value) return "—";
        const date = new Date(value.length === 10 ? value + "T00:00:00" : value);
        return Number.isNaN(date.getTime()) ? value : date.toLocaleDateString(undefined, { day: "numeric", month: "short", year: "numeric" });
    }

    function addCell(row, value) {
        const cell = document.createElement("td");
        cell.textContent = value || "—";
        row.appendChild(cell);
        return cell;
    }

    function renderMyItems() {
        const table = one(".table tbody");
        if (!table || !one("#keyword", one("form") || document)) return;
        const items = readList(STORE.items);
        if (!items.length) return;
        table.replaceChildren();
        items.forEach(function (item) {
            const row = document.createElement("tr");
            addCell(row, item.name);
            addCell(row, item.type || "Lost");
            addCell(row, displayDate(item.createdAt || item.date));
            addCell(row, item.location);
            const statusCell = document.createElement("td");
            const badge = document.createElement("span");
            badge.className = "badge text-bg-primary";
            badge.textContent = item.status || "Active";
            statusCell.appendChild(badge);
            row.appendChild(statusCell);
            const action = document.createElement("td");
            const link = document.createElement("a");
            link.href = "itemDetails.html?id=" + encodeURIComponent(item.id);
            link.className = "btn btn-sm btn-outline-primary";
            link.textContent = "View";
            action.appendChild(link);
            row.appendChild(action);
            table.appendChild(row);
        });
    }

    function renderAdminOverview() {
        const heading = one("#summaryHeading");
        if (!heading) return;
        const items = readList(STORE.items);
        const claims = readList(STORE.claims);
        const counts = { "All items": items.length, "Lost items": items.filter(x => x.type === "Lost").length, "Found items": items.filter(x => x.type === "Found").length, "Open claims": claims.filter(x => x.status === "Pending").length };
        all(".card-body").forEach(function (body) {
            const label = one("p", body);
            if (!label) return;
            const count = counts[label.textContent.trim()];
            if (count !== undefined) {
                const number = one(".h2", body);
                if (number) number.textContent = String(count);
            }
        });
        if (items.length) {
            const section = one("#itemsHeading").closest("section");
            const empty = one(".text-center", section);
            if (empty) {
                const list = document.createElement("div");
                list.className = "list-group list-group-flush";
                items.slice(0, 5).forEach(function (item) {
                    const link = document.createElement("a");
                    link.href = "itemDetails.html?id=" + encodeURIComponent(item.id);
                    link.className = "list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3";
                    const title = document.createElement("span");
                    title.className = "fw-semibold";
                    title.textContent = item.name;
                    const detail = document.createElement("small");
                    detail.className = "text-secondary ms-3";
                    detail.textContent = (item.type || "Lost") + " · " + displayDate(item.createdAt);
                    link.append(title, detail);
                    list.appendChild(link);
                });
                empty.replaceWith(list);
            }
        }
        if (claims.length) {
            const section = one("#claimsHeading").closest("section");
            const empty = one(".text-center", section);
            if (empty) {
                const list = document.createElement("ul");
                list.className = "list-group list-group-flush";
                claims.slice(0, 5).forEach(function (claim) {
                    const row = document.createElement("li");
                    row.className = "list-group-item d-flex justify-content-between py-3";
                    const name = document.createElement("span");
                    name.textContent = claim.itemName || claim.itemId || "Claim request";
                    const status = document.createElement("span");
                    status.className = "badge text-bg-warning";
                    status.textContent = claim.status || "Pending";
                    row.append(name, status);
                    list.appendChild(row);
                });
                empty.replaceWith(list);
            }
        }
    }

    function renderSearchResults(form) {
        const items = readList(STORE.items);
        const query = ((one("#keyword", form) || one("[name='keyword']", form) || {}).value || "").trim().toLowerCase();
        const category = ((one("#category", form) || {}).value || "").toLowerCase();
        const location = ((one("#location", form) || {}).value || "").toLowerCase();
        const status = ((one("#status", form) || {}).value || "").toLowerCase();
        const filtered = items.filter(function (item) {
            const terms = [item.name, item.category, item.location, item.description, item.id].join(" ").toLowerCase();
            return (!query || terms.includes(query)) && (!category || String(item.category).toLowerCase().includes(category)) && (!location || String(item.location).toLowerCase().includes(location)) && (!status || String(item.type).toLowerCase() === status || String(item.status).toLowerCase() === status);
        });
        const resultsArea = one("#sortBy") ? one("#sortBy").closest(".d-flex").nextElementSibling : null;
        if (!resultsArea) return;
        if (!filtered.length) {
            resultsArea.className = "card border-0 shadow-sm";
            resultsArea.innerHTML = "";
            const body = document.createElement("div");
            body.className = "card-body text-center text-secondary py-5";
            body.textContent = items.length ? "No saved items match those filters. Try changing your search." : "No items have been reported yet.";
            resultsArea.appendChild(body);
            return;
        }
        resultsArea.className = "row g-3";
        resultsArea.replaceChildren();
        filtered.forEach(function (item) {
            const column = document.createElement("div");
            column.className = "col-12 col-md-6 col-xl-4";
            const card = document.createElement("article");
            card.className = "card border-0 shadow-sm h-100";
            const body = document.createElement("div");
            body.className = "card-body";
            const badge = document.createElement("span");
            badge.className = "badge " + (item.type === "Found" ? "text-bg-success" : "text-bg-danger");
            badge.textContent = item.type || "Lost";
            const title = document.createElement("h3");
            title.className = "h5 fw-bold mt-3";
            title.textContent = item.name;
            const description = document.createElement("p");
            description.className = "text-secondary small";
            description.textContent = item.description || "No description provided.";
            const details = document.createElement("p");
            details.className = "small mb-3";
            details.textContent = [item.category, item.location, displayDate(item.createdAt)].filter(Boolean).join(" · ");
            const link = document.createElement("a");
            link.href = "itemDetails.html?id=" + encodeURIComponent(item.id);
            link.className = "btn btn-outline-primary btn-sm";
            link.textContent = "View details";
            body.append(badge, title, description, details, link);
            card.appendChild(body);
            column.appendChild(card);
            resultsArea.appendChild(column);
        });
    }

    function todayLocal() {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        return now.toISOString().slice(0, 10);
    }

    function setupPasswordToggles() {
        const buttons = all("#togglePassword, #toggleConfirmPassword");
        buttons.forEach(function (button) {
            const group = button.closest(".input-group");
            const input = group ? one("input[type='password'], input[type='text']", group) : one("#password");
            if (!input) return;
            button.addEventListener("click", function () {
                const show = input.type === "password";
                input.type = show ? "text" : "password";
                button.setAttribute("aria-label", show ? "Hide password" : "Show password");
                const icon = one("i", button);
                if (icon) icon.className = "bi " + (show ? "bi-eye-slash" : "bi-eye");
            });
        });
    }

    function setupRegistration() {
        const form = one("#roleFilter") && one("#roleFilter").closest("form");
        if (!form) return;
        const params = new URLSearchParams(window.location.search);
        if (params.get("error") === "invalid") {
            formMessage(form, "Check the required fields, password length, and password confirmation, then try again.", "warning");
        } else if (params.get("error") === "email") {
            formMessage(form, "An account already exists for that email address.", "warning");
        } else if (params.get("error") === "registration") {
            formMessage(form, "We could not complete registration. Check the server configuration and try again.", "danger");
        }
        form.addEventListener("submit", function (event) {
            const password = one("#registerPassword", form);
            const confirmation = one("#confirmPassword", form);
            if (!password || !confirmation || password.value !== confirmation.value) {
                event.preventDefault();
                formMessage(form, "Those passwords do not match yet. Please check them and try again.", "warning");
                if (confirmation) confirmation.focus();
                return;
            }
        });
    }

    function setupLogin() {
        const name = one("#name");
        const password = one("#password");
        const form = name && name.closest("form");
        if (!form || !password || !one("#rememberMe", form)) return;
        form.addEventListener("submit", function (event) {
            if (!name.value.trim() || !password.value) {
                event.preventDefault();
                formMessage(form, "Enter your username or email and password to continue.", "warning");
            }
        });
        const params = new URLSearchParams(window.location.search);
        const registered = params.get("registered") === "1";
        let remembered = "";
        try {
            remembered = localStorage.getItem("campusLostFound.rememberedName") || "";
        } catch (error) {
            if (!registered) formMessage(form, "This browser could not access saved sign-in details.", "warning");
        }
        if (remembered) name.value = remembered;
        if (registered) formMessage(form, "Your account was created. Sign in with your email and password.", "success");
        if (params.get("error") === "credentials") formMessage(form, "That email and password combination was not found.", "warning");
        if (params.get("error") === "server") formMessage(form, "Sign-in is temporarily unavailable. Please try again later.", "danger");
        const remember = one("#rememberMe", form);
        if (remember) form.addEventListener("submit", function () {
            if (remember.checked) localStorage.setItem("campusLostFound.rememberedName", name.value.trim());
            else localStorage.removeItem("campusLostFound.rememberedName");
        });
    }

    function setupPostItem() {
        const form = one("#lostItemForm");
        if (!form) return;
        const photoInput = one("#itemPhoto", form);
        const preview = one("#photoPreview", form);
        const date = one("#dateLost", form);
        const video = one("#cameraVideo", form);
        const canvas = one("#cameraCanvas", form);
        const open = one("#cameraButton", form);
        const capture = one("#captureButton", form);
        const close = one("#closeCamera", form);
        const message = one("#cameraMessage", form);
        let stream = null;
        let objectUrl = null;

        if (date) date.max = todayLocal();

        function showPhoto(file) {
            if (!file) return;
            if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
                if (message) message.textContent = "Please use a JPG, PNG, or WEBP image.";
                photoInput.value = "";
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                if (message) message.textContent = "That image is over 5 MB. Please choose a smaller photo.";
                photoInput.value = "";
                return;
            }
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = URL.createObjectURL(file);
            if (preview) {
                preview.src = objectUrl;
                preview.classList.remove("d-none");
            }
            if (message) message.textContent = "Photo ready. You can remove it or continue with your item report.";
        }

        function stopCamera() {
            if (stream) stream.getTracks().forEach(function (track) { track.stop(); });
            stream = null;
            if (video) {
                video.srcObject = null;
                video.classList.add("d-none");
            }
            if (capture) capture.disabled = true;
            if (close) close.disabled = true;
            if (open) open.disabled = false;
        }

        if (photoInput) photoInput.addEventListener("change", function () { showPhoto(photoInput.files[0]); });
        const remove = one("#removePhoto", form);
        if (remove) remove.addEventListener("click", function () {
            if (photoInput) photoInput.value = "";
            if (preview) { preview.src = ""; preview.classList.add("d-none"); }
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
            if (message) message.textContent = "Photo removed.";
        });
        if (open) open.addEventListener("click", async function () {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) throw new Error("Camera unavailable");
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false });
                video.srcObject = stream;
                video.classList.remove("d-none");
                capture.disabled = false;
                close.disabled = false;
                open.disabled = true;
                if (message) message.textContent = "Camera is ready. Center the item in the frame.";
            } catch (error) {
                if (message) message.textContent = "We could not open the camera. Check browser permission and use HTTPS or localhost, or upload a photo instead.";
            }
        });
        if (capture) capture.addEventListener("click", function () {
            if (!stream || !video.videoWidth) { if (message) message.textContent = "Please wait for the camera to be ready."; return; }
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext("2d").drawImage(video, 0, 0);
            canvas.toBlob(function (blob) {
                if (!blob) { if (message) message.textContent = "The photo could not be captured. Please try again."; return; }
                const file = new File([blob], "item-photo.jpg", { type: "image/jpeg" });
                try {
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    photoInput.files = transfer.files;
                } catch (error) { /* The preview remains available where file assignment is restricted. */ }
                stopCamera();
                showPhoto(file);
                if (message) message.textContent = "Photo captured. It is ready to go with your item report.";
            }, "image/jpeg", 0.9);
        });
        if (close) close.addEventListener("click", function () { stopCamera(); if (message) message.textContent = "Camera closed."; });
        window.addEventListener("pagehide", stopCamera);
        form.addEventListener("reset", function () {
            stopCamera();
            if (preview) { preview.src = ""; preview.classList.add("d-none"); }
            if (message) message.textContent = "";
        });
    }

    function setupTakePhoto() {
        const video = one("#camera");
        const canvas = one("#canvas");
        const preview = one("#photoPreview");
        const open = one("#openCamera");
        const take = one("#takePhoto");
        const retake = one("#retakePhoto");
        const close = one("#closeCamera");
        const upload = one("#uploadPhoto");
        const save = one("#savePhoto");
        const message = one("#message");
        if (!video || !canvas || !open || !message) return;
        let stream = null;
        let photoData = "";

        function stopCamera() {
            if (stream) stream.getTracks().forEach(function (track) { track.stop(); });
            stream = null;
            video.srcObject = null;
            video.style.display = "none";
            if (close) close.disabled = true;
        }
        function showPhoto(data) {
            photoData = data;
            preview.src = data;
            preview.style.display = "block";
            video.style.display = "none";
            take.disabled = true;
            open.disabled = false;
            close.disabled = true;
            save.disabled = false;
        }

        open.addEventListener("click", async function () {
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) throw new Error("Camera not available");
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false });
                video.srcObject = stream;
                video.style.display = "block";
                preview.style.display = "none";
                take.disabled = false;
                close.disabled = false;
                open.disabled = true;
                message.textContent = "Camera is ready. Position the item and take your photo when it looks clear.";
            } catch (error) {
                message.textContent = "We could not open the camera. Check your browser permission and use HTTPS or localhost, or upload a photo instead.";
            }
        });
        take.addEventListener("click", function () {
            if (!video.videoWidth) { message.textContent = "Please wait for the camera to be ready."; return; }
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext("2d").drawImage(video, 0, 0);
            showPhoto(canvas.toDataURL("image/jpeg", 0.9));
            stopCamera();
            if (retake) retake.style.display = "inline-block";
            message.textContent = "Photo captured. Take another one if you would like, or use this photo.";
        });
        if (retake) retake.addEventListener("click", function () {
            photoData = "";
            preview.style.display = "none";
            retake.style.display = "none";
            save.disabled = true;
            open.disabled = false;
            open.click();
        });
        if (close) close.addEventListener("click", function () { stopCamera(); take.disabled = true; open.disabled = false; message.textContent = "Camera closed. You can reopen it or upload a photo."; });
        if (upload) upload.addEventListener("change", function () {
            const file = upload.files && upload.files[0];
            if (!file) return;
            if (!file.type.startsWith("image/")) { upload.value = ""; message.textContent = "Please choose an image file."; return; }
            if (file.size > 5 * 1024 * 1024) { upload.value = ""; message.textContent = "That photo is over 5 MB. Please choose a smaller one."; return; }
            stopCamera();
            const reader = new FileReader();
            reader.onload = function () {
                showPhoto(reader.result);
                if (retake) retake.style.display = "none";
                message.textContent = "Photo uploaded. Take a moment to check the preview.";
            };
            reader.onerror = function () { message.textContent = "We could not read that photo. Please try another file."; };
            reader.readAsDataURL(file);
        });
        save.addEventListener("click", function () {
            if (!photoData) return;
            try {
                sessionStorage.setItem(STORE.photo, photoData);
                message.textContent = "Your photo is ready and saved for this browser session.";
            } catch (error) {
                message.textContent = "This photo is too large to keep in browser storage. You can still use the preview on this page.";
            }
        });
        window.addEventListener("pagehide", stopCamera);
    }

    function setupClaimForms() {
        all("form").forEach(function (form) {
            const claimItem = one("#itemId", form) || one("[name='found_item_id']", form);
            if (!claimItem) return;
            const selectedId = new URLSearchParams(window.location.search).get("item");
            if (selectedId) claimItem.value = selectedId;
        });
    }

    function setupProfile() {
        const newPassword = one("#newPassword");
        const passwordForm = newPassword && newPassword.closest("form");
        if (passwordForm) passwordForm.addEventListener("submit", function (event) {
            const confirm = one("#confirmPassword", passwordForm);
            if (confirm && newPassword.value !== confirm.value) {
                event.preventDefault();
                formMessage(passwordForm, "The new passwords do not match yet. Please check and try again.", "warning");
                confirm.focus();
            }
        });
    }

    function setupSearchForms() {}

    function setupArchiveSearch() {}

    function renderDashboard() {
        const main = all("main").find(function (region) { return region.textContent.includes("My Lost Items") && region.textContent.includes("My Found Items"); });
        if (!main) return;
        const items = readList(STORE.items);
        const claims = readList(STORE.claims);
        const counts = { "My Lost Items": items.filter(item => item.type === "Lost").length, "My Found Items": items.filter(item => item.type === "Found").length, "My Claim Requests": claims.length };
        all(".card-body", main).forEach(function (body) {
            const label = one("p", body);
            if (!label) return;
            const value = counts[label.textContent.trim()];
            const number = one("h2", body);
            if (value !== undefined && number) number.textContent = String(value);
        });
    }

    function renderItemDetails() {
        const heading = one("#emptyHeading");
        if (!heading) return;
        const id = new URLSearchParams(window.location.search).get("id");
        if (!id) return;
        const item = readList(STORE.items).find(function (entry) { return entry.id === id; });
        if (!item) return;
        const body = heading.closest(".card-body");
        if (!body) return;
        body.replaceChildren();
        const kicker = document.createElement("p");
        kicker.className = "text-uppercase text-primary fw-semibold small mb-2";
        kicker.textContent = item.type || "Lost item";
        const title = document.createElement("h1");
        title.className = "h3 fw-bold mb-3";
        title.textContent = item.name;
        const description = document.createElement("p");
        description.className = "text-secondary mb-4";
        description.textContent = item.description || "No description was added.";
        const details = document.createElement("dl");
        details.className = "row text-start mx-auto mb-4";
        [["Reference", item.id], ["Category", item.category], ["Location", item.location], ["Date reported", displayDate(item.createdAt || item.date)], ["Status", item.status || "Active"]].forEach(function (entry) {
            const term = document.createElement("dt");
            term.className = "col-sm-4 text-secondary fw-normal";
            term.textContent = entry[0];
            const value = document.createElement("dd");
            value.className = "col-sm-8";
            value.textContent = entry[1] || "—";
            details.append(term, value);
        });
        const actions = document.createElement("div");
        actions.className = "d-flex flex-wrap justify-content-center gap-2";
        const back = document.createElement("a");
        back.href = "searchItems.html";
        back.className = "btn btn-outline-secondary";
        back.textContent = "Back to items";
        actions.appendChild(back);
        if (item.type === "Found") {
            const claim = document.createElement("a");
            claim.href = "claimRequest.html?item=" + encodeURIComponent(item.id);
            claim.className = "btn btn-primary";
            claim.textContent = "Request this item";
            actions.appendChild(claim);
        }
        body.append(kicker, title, description, details, actions);
    }

    function init() {
        setupPasswordToggles();
        setupRegistration();
        setupLogin();
        setupPostItem();
        setupTakePhoto();
        setupClaimForms();
        setupProfile();
        renderAdminOverview();
        renderItemDetails();
    }

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
    else init();
}());
