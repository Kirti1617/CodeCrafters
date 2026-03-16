var labels = [];
let detectedFaces = [];
let sendingData = false;

function updateTable() {
  var selectedCourseID = document.getElementById("courseSelect").value;
  var selectedUnitCode = document.getElementById("unitSelect").value;
  var selectedVenue = document.getElementById("venueSelect").value;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "resources/pages/lecture/manageFolder.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.status === "success") {
        labels = response.data;

        if (selectedCourseID && selectedUnitCode && selectedVenue) {
          updateOtherElements();
        }
        document.getElementById("studentTableContainer").innerHTML =
          response.html;
      } else {
        console.error("Error:", response.message);
      }
    }
  };
  xhr.send(
    "courseID=" +
      encodeURIComponent(selectedCourseID) +
      "&unitID=" +
      encodeURIComponent(selectedUnitCode) +
      "&venueID=" +
      encodeURIComponent(selectedVenue)
  );
}

function markAttendance(detectedFaces) {
  document.querySelectorAll("#studentTableContainer tr").forEach((row) => {
    const registrationNumber = row.cells[0].innerText.trim();
    if (detectedFaces.includes(registrationNumber)) {
      row.cells[5].innerText = "present";
    }
  });
}

function updateOtherElements() {
  const video = document.getElementById("video");
  const videoContainer = document.querySelector(".video-container");
  const startButton = document.getElementById("startButton");
  let webcamStarted = false;
  let modelsLoaded = false;

  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  ])
    .then(() => {
      modelsLoaded = true;
      console.log("models loaded successfully");
    })
    .catch(() => {
      alert("models not loaded, please check your model folder location");
    });
  startButton.addEventListener("click", async () => {
    videoContainer.style.display = "flex";
    if (!webcamStarted && modelsLoaded) {
      startWebcam();
      webcamStarted = true;
    }
  });

function startWebcam() {
    navigator.mediaDevices
      .getUserMedia({
        video: true,
        audio: false,
      })
      .then((stream) => {
        video.srcObject = stream;
        videoStream = stream;
      })
      .catch((error) => {
        console.error(error);
      });
  }
  async function getLabeledFaceDescriptions() {
    const labeledDescriptors = [];

    for (const label of labels) {
      const descriptions = [];

      for (let i = 1; i <= 5; i++) {
        try {
          const img = await faceapi.fetchImage(
            `resources/labels/${label}/${i}.png`
          );
          const detections = await faceapi
            .detectSingleFace(img)
            .withFaceLandmarks()
            .withFaceDescriptor();

          if (detections) {
            descriptions.push(detections.descriptor);
          } else {
            console.log(`No face detected in ${label}/${i}.png`);
          }
        } catch (error) {
          console.error(`Error processing ${label}/${i}.png:`, error);
        }
      }

