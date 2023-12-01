console.log("312471270");
        function checkCode() {
            let correctCode = "312471270"; // The correct code based on the above clues
            let playerCode = document.querySelector(".codeInput").value;

            if (playerCode === correctCode) {
                document.querySelector(".victoryMessage").classList.remove("hidden");
                document.querySelector(".clues").classList.add("hidden");
            } else {
                alert("Incorrect code. Try again!");
            }
        }

        function navigateToKamer4() {
            // Redirect to the "kamer4.html" page
            window.location.href = "kamer4.html";
        }

        // Modal functions
        function openModal() {
            document.querySelector(".modal").style.display = "block";
        }

        function closeModal() {
            document.querySelector(".modal").style.display = "none";
        }

        // Show modal when the button is clicked
        document.querySelector(".myBtn").addEventListener("click", openModal);