<?php
include_once 'includes/authentication.php';
require_once 'lib/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html = "
<style>
    table {
        width:100%;
    }
    table, tr, td, th {
        border: solid 1px black;
    }
</style>
<h1>Recipes</h1>
<table> 
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Ingredients</th>
        </tr>
    </thead>
    <tbody>
";

$recipes = $db->query('select * from recipe');

foreach ($recipes as $recipe) {
    $ingredientsHtml = '';
    $ingredients = $db->query("
        select ir.amount as amount, ingredient.name as name, ingredient.type as type from ingredientOnRecipe ir
        inner join ingredient on ingredient.id = ir.ingredient
        where ir.recipe = {$recipe['id']}
    ");

    foreach ($ingredients as $ingredient) {
        $type = $ingredient['type'] == 0 ? 'mg' : 'mL';
        $ingredientsHtml .= "<li>{$ingredient['name']} {$ingredient['amount']} $type</li>";
    }


    $html .= "
        <tr>
            <td>{$recipe['id']}</td>
            <td>{$recipe['name']}</td>
            <td><ul>$ingredientsHtml</ul></td>
        </tr>
    ";
}


$dompdf->loadHtml($html.'</tbody></table>');
$dompdf->render();
$dompdf->stream('report', array("Attachment" => false));
