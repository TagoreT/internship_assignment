<?php
$payload = $_POST["github-url"];
$user_name = $_POST["name"];

if (!empty($user_name)) {
  $contributorName = $_POST["name"];
  $reposUrl = "https://api.github.com/users/{$contributorName}/repos";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $reposUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  $reposData = curl_exec($ch);
  curl_close($ch);

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPassword = "";
  $dbName = "github_data";

  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO repositories (id, name, html_url, description, created_at, open_issues, watchers, owner_id, owner_avatar_url, owner_html_url, owner_type, owner_site_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), html_url=VALUES(html_url), description=VALUES(description), created_at=VALUES(created_at), open_issues=VALUES(open_issues), watchers=VALUES(watchers), owner_id=VALUES(owner_id), owner_avatar_url=VALUES(owner_avatar_url), owner_html_url=VALUES(owner_html_url), owner_type=VALUES(owner_type), owner_site_admin=VALUES(owner_site_admin)");

  $stmt->bind_param("issssiissssi", $id, $name, $htmlUrl, $description, $createdAt, $openIssues, $watchers, $ownerId, $ownerAvatarUrl, $ownerHtmlUrl, $ownerType, $ownerSiteAdmin);

  $reposData = json_decode($reposData, true);

  if (is_array($reposData)) {
    foreach ($reposData as $repo) {
      $id = $repo['id'];
      $name = $repo['name'];
      $htmlUrl = $repo['html_url'];
      $description = $repo['description'];
      $createdAt = $repo['created_at'];
      $openIssues = $repo['open_issues'];
      $watchers = $repo['watchers'];
      $ownerId = $repo['owner']['id'];
      $ownerAvatarUrl = $repo['owner']['avatar_url'];
      $ownerHtmlUrl = $repo['owner']['html_url'];
      $ownerType = $repo['owner']['type'];
      $ownerSiteAdmin = $repo['owner']['site_admin'];

      $stmt->execute();
    }
  }

  $stmt->close();
  $conn->close();
  $Message = urlencode("Repositories data saved successfully");
  header("Location:index.php?Message=".$Message);
  die;
} else if (!empty($payload)) {
  $url = $payload;
  $data = array('url' => $url);
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  $reposData = curl_exec($ch);
  curl_close($ch);

  $reposData = json_decode($reposData, true);

  $dbHost = "localhost";
  $dbUser = "root";
  $dbPassword = "";
  $dbName = "github_data";

  $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO repositories (id, name, html_url, description, created_at, open_issues, watchers, owner_id, owner_avatar_url, owner_html_url, owner_type, owner_site_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), html_url=VALUES(html_url), description=VALUES(description), created_at=VALUES(created_at), open_issues=VALUES(open_issues), watchers=VALUES(watchers), owner_id=VALUES(owner_id), owner_avatar_url=VALUES(owner_avatar_url), owner_html_url=VALUES(owner_html_url), owner_type=VALUES(owner_type), owner_site_admin=VALUES(owner_site_admin)");

  $stmt->bind_param("issssiissssi", $id, $name, $htmlUrl, $description, $createdAt, $openIssues, $watchers, $ownerId, $ownerAvatarUrl, $ownerHtmlUrl, $ownerType, $ownerSiteAdmin);

  foreach ($reposData as $repo) {
    $id = $repo['id'];
    $name = $repo['name'];
    $htmlUrl = $repo['html_url'];
    $description = $repo['description'];
    $createdAt = $repo['created_at'];
    $openIssues = $repo['open_issues'];
    $watchers = $repo['watchers'];
    $ownerId = $repo['owner']['id'];
    $ownerAvatarUrl = $repo['owner']['avatar_url'];
    $ownerHtmlUrl = $repo['owner']['html_url'];
    $ownerType = $repo['owner']['type'];
    $ownerSiteAdmin = $repo['owner']['site_admin'];

    $stmt->execute();
  }

  $stmt->close();
  $conn->close();

  $Message = urlencode("Repositories data saved successfully");
  header("Location:index.php?Message=".$Message);
  die;
} else {
  echo "incorrect payload or URL";
}

?>
