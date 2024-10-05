document
    .getElementById("purchaseForm")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        const productId = document.getElementById("productId").value;

        // URL API untuk melakukan pembelian
        const apiUrl = `http://127.0.0.1:8000/api/products/${productId}/purchase`;

        fetch(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                productId: productId,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    alert(`Purchase successful! New stock: ${data.newStock}`);
                    document.getElementById(
                        "result"
                    ).innerText = `Purchase successful! New stock: ${data.newStock}`;
                } else {
                    alert(`Error: ${data.message}`);
                    document.getElementById(
                        "result"
                    ).innerText = `Error: ${data.message}`;
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred: " + error.message);
                document.getElementById("result").innerText =
                    "An error occurred.";
            });
    });
