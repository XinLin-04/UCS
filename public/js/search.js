       // Search Icon Toggle Function
       const toggleSearch = (search, button) =>{
        const searchBar = document.getElementById(search),
                searchButton = document.getElementById(button)
    
        searchButton.addEventListener('click', () =>{
            //Add show-search class, so that the search bar expands
            searchBar.classList.toggle('show-search')
        })
    }
    
    // Initialize the toggleSearch function
    toggleSearch('search-bar', 'search-button');
    
        document.getElementById("search").addEventListener("input", Search);
    document.getElementById("search-button").addEventListener("click", function () {
      // Additional functionality for search button can go here
    });
    
    function Search() {
      var searchInput = document.getElementById("search").value;
      var searchResultDiv = document.getElementById("search_result");
    
      // Clear and hide results if the search input is empty
      if (searchInput.trim() === "") {
        searchResultDiv.innerHTML = ""; // Clear the search results
        searchResultDiv.style.display = "none"; // Hide the search results container
        return; // Exit the function if input is empty
      }
    
      // If there's input, display the search results container
      searchResultDiv.style.display = "inline";
    
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "includes/search.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          document.getElementById("search_result").innerHTML = xhr.responseText;
        } else if (xhr.readyState == 4) {
          alert("There was a problem with the request.");
        }
      };
      xhr.send("name=" + encodeURIComponent(searchInput));
    }
    
    // Function to clear search input and hide search results
    const clearInput = () => {
      const searchInput = document.querySelector(".search__input");
      const xIcon = document.querySelector(".bx-x");
      const searchResultDiv = document.querySelector("#search_result");
    
      if (searchInput && xIcon) {
        xIcon.addEventListener("click", () => {
          searchInput.value = "";
          searchResultDiv.innerHTML = ""; // Clear search results
          searchResultDiv.style.display = "none"; // Hide the search results container
        });
      }
    };
    
    // Function to hide search results when clicking outside the search area
    function handleClickOutside(event) {
      const searchInput = document.getElementById("search");
      const searchResultDiv = document.getElementById("search_result");
    
      // Check if the click is outside the search input or result area
      if (
        !searchInput.contains(event.target) &&
        !searchResultDiv.contains(event.target)
      ) {
        searchResultDiv.style.display = "none"; // Hide the search results container
      }
    }
    
    // Show search results again when the input is focused
    document.getElementById("search").addEventListener("focus", function () {
      const searchResultDiv = document.getElementById("search_result");
      if (this.value.trim() !== "") {
        searchResultDiv.style.display = "inline"; // Show the search results container
      }
    });
    
    // Add a global click event listener to the document
    document.addEventListener("click", handleClickOutside);
    
    // Initialize the clearInput functionality
    clearInput();