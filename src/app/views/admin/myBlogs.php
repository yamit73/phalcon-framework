<?php
global $settings;
?>
<h2>My Blogs</h2>
<div class="table-responsive">
    <table class="table table-sm table-hover">
        <thead class="bg-dark text-light">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Category Id</th>
                <th scope="col">Title</th>
                <th scope="col">Topic</th>
                <th scope="col">Description</th>
                <th scope="col">Review date</th>
                <th scope="col">Publish date</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $row='';
            foreach ($dat as $val) {
                $row .= '<tr>
                            <th scope="row">'.$val['id'].'</th>
                            <td>'.$val['category_id'].'</td>
                            <td>'.$val['post_title'].'</td>
                            <td>'.$val['post_topic'].'</td>
                            <td>'.$val['post_description'].'</td>
                            <td>'.$val['review_date'].'</td>
                            <td>'.$val['publish_date'].'</td>
                            <td>'.$val['status'].'</td>
                        </tr>';
            }
            echo $row;
            ?>
            
        </tbody>
    </table>
</div>