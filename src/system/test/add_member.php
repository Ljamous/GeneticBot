<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Family Member</title>
</head>
<body>
    <h2>Add Family Member</h2>
    <form action="add_member.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="parent_id">Parent:</label>
        <select id="parent_id" name="parent_id">
            <option value="">None</option>
            <?php
            $result = $conn->query("SELECT id, name FROM members");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Add Member">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $parent_id = $_POST['parent_id'] ? $_POST['parent_id'] : 'NULL';

        $sql = "INSERT INTO members (name, parent_id) VALUES ('$name', $parent_id)";
        
        if ($conn->query($sql) === TRUE) {
            echo "New member added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
    ?>
</body>
</html>
