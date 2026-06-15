<?php
session_start(); 
require_once 'mycon.php';

function get_family_tree($parent_id, $conn) {
    global $con;
    $tree = [];
    $sql = "SELECT id, name, gender FROM members WHERE parent_id " . ($parent_id ? "= $parent_id" : "IS NULL");
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $children = get_family_tree($row['id'], $con);
        if ($children) {
            $row['children'] = $children;
        }
        $tree[] = $row;
    }
    return $tree;
}

// Set a custom label for the root node
$tree = get_family_tree(null, $con);
echo json_encode(['name' => 'Root', 'children' => $tree]);


?>
