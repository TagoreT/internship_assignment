<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = $_GET['id'];

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPassword = "";
  $dbName = "github_data";
  
  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  $stmt = $conn->prepare("SELECT * FROM repositories WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

  $repositoryData = json_encode($data);


  if (!empty($data)) {
    $repositoryData = json_decode($repositoryData, true);

    echo '<div class="repository-card">';
    echo '<h3>' . $repositoryData['name'] . '</h3>';
    echo '<p>' . $repositoryData['html_url'] . '</p>';
    echo '<p>' . $repositoryData['description'] . '</p>';
    echo '<p>Created At: ' . $repositoryData['created_at'] . '</p>';
    echo '<p>Open Issues: ' . $repositoryData['open_issues'] . '</p>';
    echo '<p>Watchers: ' . $repositoryData['watchers'] . '</p>';
    echo '<div class="owner-info">';
    echo '<p>Owner: <a href="' . $repositoryData['owner_html_url'] . '">' . $repositoryData['owner_id'] . '</a></p>';
    echo '<p>Type: ' . $repositoryData['owner_type'] . '</p>';
    echo '<p>Site Admin: ' . ($repositoryData['owner_site_admin'] ? 'Yes' : 'No') . '</p>';
    echo '</div>';
    echo '</div>';
  }else {
    echo "Error: Failed to fetch repository data.";
  }
}
?>
