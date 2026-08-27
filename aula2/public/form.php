

-------------------------- POST no mesmo form
<form method="POST">
<label for="num1">Número 1</label>
<input type="number" name="numero1" id="numero1" required >
<br><br>
<label for="num2">Número 2</label>
<input type="number" name="numero2" id="numero2" required>
<br><br>
<input type="submit" class="btn btn-primary" value="Post Mesmo Form">
</form>

<hr>
-------------- POST para outra página
<form method="POST" action="recebe.php">
<label for="num1">Número 1</label>
<input type="number" name="numero1" id="numero1" required >
<br><br>
<label for="num2">Número 2</label>
<input type="number" name="numero2" id="numero2" required>
<br><br>
<input type="submit"  class="btn btn-success" value="Post outra página">
</form>
<hr>
--------------------- GET para outra página

<form method="GET" action="recebe.php">
<label for="num1">Número 1</label>
<input type="number" name="numero1" id="numero1" required >
<br><br>
<label for="num2">Número 2</label>
<input type="number" name="numero2" id="numero2" required>
<br><br>
<input type="submit" class="btn btn-danger" value="Post outra página">
</form>