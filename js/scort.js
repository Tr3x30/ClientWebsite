const images  = [
    "images/20230420_164510000_iOS.jpg",
    "images/Team_image1.png",
    "images/Team_images2.png"
]

let index = 0;

function changeBanner() {

    index++;

    if (index >= images.length){
        index = 0;
    }

    document.getElementById("image").style.backgroundImage = 
        "url('" + images[index] + "')";
}

//change every 3 seconds
setInterval(changeBanner, 3000);