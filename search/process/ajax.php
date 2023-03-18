<?php
//db constants
define('DB_HOST', 'localhost');
define('DB_HOST_USER', 'root');
define('DB_HOST_USER_PASSWORD', 'root');
define('DB_NAME', 'phpsearch');
//connect to db 
try {
    //try PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_HOST_USER, DB_HOST_USER_PASSWORD);
    //set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected to database";
} catch (\PDOException $e) {
    //if error, throw error
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

//check if isset $_POST['search'];
if (isset($_POST['search'])) {
    //if isset, set variable $search to $_POST['search']
    $search = $_POST['search'];
    //pass %
    $search = '%' . $search . '%';
    //prepare sql statement
    $sql = "SELECT * FROM blog WHERE name LIKE :search";
    //prepare statement
    $stmt = $pdo->prepare($sql);
    //bind parameters
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    //execute statement
    $stmt->execute();
    //fetch all results
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    //check if results are empty
    if (empty($results)) {
        //if empty, echo no results
        echo "No results found";
    } else {
?>
        <ul>
            <?php
            //if not empty, loop through results
            foreach ($results as $result) :
            ?>
                <li>
                    <img src="<?php echo $result->image ?>" alt="">
                    <p><?php echo $result->name ?></p>
                </li>
            <?php
            endforeach;
            ?>
        </ul>
<?php
    }
}
