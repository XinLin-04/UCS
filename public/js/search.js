// Search Icon Toggle Function
const toggleSearch = (search, button) => {
    const searchBar = document.getElementById(search),
        searchButton = document.getElementById(button);

    searchButton.addEventListener("click", () => {
        //Add show-search class, so that the search bar expands
        searchBar.classList.toggle("show-search");
    });
};

// Initialize the toggleSearch function
toggleSearch("search-bar", "search-button");

document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search");
    const postsContainer = document.getElementById("all-posts");

    // Function to fetch and display search results
    function fetchSearchResults(query = "") {
        fetch("/search", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ query }),
        })
            .then((response) => response.json())
            .then((data) => {
                // Clear previous posts
                postsContainer.innerHTML = "";
    
                if (data.length > 0) {
                    // Display search results
                    data.forEach(complaint => {
                        const postElement = document.createElement("a"); // Use <a> for clickable links
                        postElement.classList.add("discussion-post");
                        postElement.setAttribute("data-id", complaint.id);
                        postElement.href = `/complaints/${complaint.id}`; // Link to complaint details page
    
                        postElement.innerHTML = `
                            <h3>${complaint.title}</h3>
                            <p class="post-content">${complaint.content}</p>
                            <div class="post-meta">
                                <span class="post-author">By: ${complaint.user.name}</span>
                                <span class="post-date">${new Date(complaint.created_at).toLocaleDateString()}</span>
                            </div>
                        `;
    
                        postsContainer.appendChild(postElement);
                    });
                } else {
                    // Display "No results found" message
                    postsContainer.innerHTML =
                        "<p>No posts found matching your search query.</p>";
                }
            })
            .catch((error) => {
                console.error("Error fetching search results:", error);
            });
    }

    // Event listener for search input
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.trim();

        if (query.length >= 3) {
            fetchSearchResults(query); // Fetch filtered results
        } else if (query === "") {
            fetchSearchResults(); // Fetch all recent posts when input is blank
        } else {
            // Optionally, show a message for short queries
            postsContainer.innerHTML =
                "<p>Please enter at least 3 characters to search.</p>";
        }
    });

    // Fetch all recent posts on page load
    fetchSearchResults();
});
