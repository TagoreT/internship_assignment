<!-- <?php
require './github_post.php';
?> -->

<!DOCTYPE html>
<html>
<head>
  <title>GitHub Repository Data</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h1>GitHub Repository Data</h1>

  <div class="form-container">
    <form action="github_post.php" method="post">
      <label for="id">GitHub API URL: </label>
      <input type="text" name="github-url" placeholder="Enter GitHub API URL"> <br>
      <label for="id">User Name: </label>
      <input type="text" name="name" placeholder="Enter GitHub User Name">
      <button type="submit">Fetch Data</button> <br>
    </form>
    <br>
    
    <div id="message"> <?php if(isset($_GET['Message'])){ echo $_GET['Message'];}?> </div>
  </div>

  <div id="repository-container">
    <h2>Repository Details</h2>
    <div id="repository-data"> </div>
    
  </div>
  <?php
    if (isset($_GET['id'])) {
      include 'script.php';
    }
  ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>
  <script>
    $(document).ready(function() {
      function fetchRepositoryData(id) {
        $.ajax({
          url: 'script.php' + id,
          type: 'GET',
          success: function(response) {
            var repositoryData = JSON.parse(response);

            var repositoryCard = $('<div class="repository-card">');
            $('<h3>').text(repositoryData.name).appendTo(repositoryCard);
            $('<p>').text(repositoryData.html_url).appendTo(repositoryCard);
            $('<p>').text(repositoryData.description).appendTo(repositoryCard);
            $('<p>').text('Created At: ' + repositoryData.created_at).appendTo(repositoryCard);
            $('<p>').text('Open Issues: ' + repositoryData.open_issues).appendTo(repositoryCard);
            $('<p>').text('Watchers: ' + repositoryData.watchers).appendTo(repositoryCard);
            var ownerInfo = $('<div class="owner-info">');
            $('<p>').html('Owner: <a href="' + repositoryData.owner_html_url + '">' + repositoryData.owner_id + '</a>').appendTo(ownerInfo);
            $('<p>').text('Type: ' + repositoryData.owner_type).appendTo(ownerInfo);
            $('<p>').text('Site Admin: ' + (repositoryData.owner_site_admin ? 'Yes' : 'No')).appendTo(ownerInfo);
            ownerInfo.appendTo(repositoryCard);
            repositoryCard.appendTo('#repository-data');
          },
          error: function(response) {
            $('#repository-data').append('');
          }
        });
      }
      var params = new URLSearchParams(window.location.search);
      var id = params.get('id');
      if (id) {
        fetchRepositoryData(id);
      }
    });
    </script>
        
    
</body>
</html>