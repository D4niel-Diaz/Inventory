<?php
include_once '../connection.php';

try {
    $col = ['position', 'position_limit'];

    // Step 1: Fetch total count
    $sql = "SELECT COUNT(*) AS total FROM position";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $totalData = $row['total'];  
    $stmt->close(); 

    $sql = "SELECT position_id, position, position_limit, position_description FROM position";
    
    if (!empty($_REQUEST['search']['value'])) {
        $searchValue = "%" . $_REQUEST['search']['value'] . "%";
        $sql .= " WHERE position LIKE ? OR position_description LIKE ? OR position_limit LIKE ?";
    }

    if (isset($_REQUEST['order'])) {
        $sql .= ' ORDER BY ' . $col[$_REQUEST['order']['0']['column']] . ' ' . $_REQUEST['order']['0']['dir'];
    } else {
        $sql .= ' ORDER BY position_id DESC';
    }

    if ($_REQUEST['length'] != -1) {
        $sql .= ' LIMIT ?, ?';
    }

    $stmt = $con->prepare($sql);

    // Bind parameters if search is used
    if (!empty($_REQUEST['search']['value'])) {
        $stmt->bind_param("sss", $searchValue, $searchValue, $searchValue);
    }

    // Bind limit parameters
    if ($_REQUEST['length'] != -1) {
        $start = intval($_REQUEST['start']);
        $length = intval($_REQUEST['length']);
        $stmt->bind_param("ii", $start, $length);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $subdata = [];
        $subdata[] = strtoupper($row['position']);
        $subdata[] = $row['position_limit'];
        $subdata[] = '<i style="cursor: pointer; color: yellow; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-edit text-lg px-3 viewPosition" id="' . $row['position_id'] . '"></i>
                      <i style="cursor: pointer; color: red; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-times text-lg px-3 deletePosition" id="' . $row['position_id'] . '"></i>';
        $data[] = $subdata;
    }


    $json_data = [
        'draw' => intval($_REQUEST['draw']),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalData),
        'data' => $data,
    ];

    echo json_encode($json_data);

} catch (Exception $e) {
    echo $e->getMessage();
}
?>
