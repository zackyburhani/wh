<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form>
  <fieldset>
 
    <label for="name">username</label>
    <input id="name" type="text" 
    pattern="[a-zA-Z]+"
    placeholder="username"
    autofocus required
    oninvalid="this.setCustomValidity('Input hanya boleh huruf!')">  
    <button type="submit">
      Simpan
    </button>
  </fieldset>
</form>
</body>
</html>