const itemsPerPage = 8;
let currentPage = 1;
let isLoading = false;
const artworkContainer = document.querySelector(".artwork-container");

function listContainer(className, content) {
  const divElement = document.createElement("div");
  divElement.className = className;
  divElement.innerHTML = content;

  // Add the animation class to the newly created element
  divElement.classList.add("animate-in");

  // Add an event listener to remove the animation class after the animation ends
  divElement.addEventListener("animationend", () => {
    divElement.classList.remove("animate-in");
  });

  artworkContainer.appendChild(divElement);

  animationDelay();
}

async function fetchArtworks(page, perPage) {
  if (isLoading) return;

  isLoading = true;

  const API_URL = `https://demo.espaciomanilagallery.com/wp-json/wl/v1/artworks?per_page=${perPage}&page=${page}`;

  try {
    const response = await fetch(API_URL);

    if (!response.ok) {
      throw new Error("Network response was not okay");
    }

    const artworks = await response.json();

    artworks.map((artwork) => {
      const content = `
      <h4>${artwork.title}</h4>
      <p>${artwork.artwork_type}</p>
      `;
      listContainer("artwork-item", content);
    });

    currentPage += 1;
  } catch (e) {
    console.error("There was a problem with the fetch operation: ", e);
  } finally {
    isLoading = false;
  }
}

function checkScroll() {
  const artScrollTop = artworkContainer.scrollTop;
  const artHeight = artworkContainer.clientHeight;
  const artScrollHeight = artworkContainer.scrollHeight;

  if (artScrollTop + artHeight >= artScrollHeight) {
    fetchArtworks(currentPage, itemsPerPage);   
  }
}

function animationDelay() {
  // Select all div elements with the "animate-in" class
  const animatedDivs = document.querySelectorAll(".animate-in");

  // Loop through each div and set a different animation delay
  animatedDivs.forEach((div, index) => {
    // Calculate the delay value based on the index or any other logic you want
    const delay = index * 0.2 + 0.2; // Example: delay increases by 0.2 seconds for each div

    // Apply the animation-delay property to the div's style
    div.style.animationDelay = `${delay}s`;
  });
}

window.addEventListener("scroll", checkScroll);
fetchArtworks(currentPage, itemsPerPage);
