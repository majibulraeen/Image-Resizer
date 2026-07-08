<?php

$message = "";
$output = "";

if (isset($_POST['resize'])) {

    $width  = $_POST['width'];
    $height = $_POST['height'];

    $image = $_FILES['image']['tmp_name'];


    // Get image information
    $imageInfo = getimagesize($image);
    $imageType = $imageInfo[2];


    // Create image from uploaded file
    switch ($imageType) {

        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($image);
            break;

        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($image);
            break;

        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($image);
            break;

        default:
            die("Only JPG, PNG and GIF images are allowed.");
    }


    // Original dimensions
    $originalWidth  = imagesx($sourceImage);
    $originalHeight = imagesy($sourceImage);


    // Create new image
    $newImage = imagecreatetruecolor($width, $height);


    // Preserve transparency
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);


    // Resize image
    imagecopyresampled(
        $newImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $width,
        $height,
        $originalWidth,
        $originalHeight
    );


    // Save image inside Images folder
    $fileName = "resized_" . time() . ".png";

    // Folder path (code.php and Images folder are in same location)
    $output = "Images/" . $fileName;


    // Save resized image
    imagepng($newImage, $output);


    // Clear memory
    imagedestroy($sourceImage);
    imagedestroy($newImage);


    $message = "Image resized successfully!";

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Smart Image Resizer</title>


<style>


*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI',sans-serif;
}


body{

    min-height:100vh;
    background:rgb(242, 242, 242);
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;

}



.container{

    width:450px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.25);

}



h2{

    text-align:center;
    color:#333;
    margin-bottom:25px;

}



.form-group{

    margin-bottom:20px;

}



label{

    display:block;
    font-weight:600;
    color:#555;
    margin-bottom:8px;

}



input{

    width:100%;
    padding:12px;
    border:2px solid #ddd;
    border-radius:10px;
    outline:none;
    transition:.3s;

}



input:focus{

    border-color:#667eea;
    box-shadow:0 0 10px #667eea55;

}




button{

    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;

}



button:hover{

    transform:translateY(-3px);
    box-shadow:0 10px 20px #667eea66;

}



.preview{

    text-align:center;
    margin-top:20px;

}



.preview img{

    max-width:100%;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);

}




.success{

    text-align:center;
    color:#28a745;
    font-size:18px;
    font-weight:bold;
    margin-bottom:20px;

}



.download{

    margin-top:20px;
    text-align:center;

}



.download a{

    text-decoration:none;
    background:#28a745;
    color:white;
    padding:12px 25px;
    border-radius:30px;
    font-weight:bold;
    display:inline-block;
    transition:.3s;

}



.download a:hover{

    background:#218838;
    transform:scale(1.05);

}




@media(max-width:500px){

.container{

width:100%;

}

}



</style>


</head>



<body>



<div class="container">


<h2>
   Smart Image Resizer
</h2>



<form method="post" enctype="multipart/form-data">



<div class="form-group">

<label>Select Image</label>

<input 
type="file" 
name="image"
id="imageInput"
accept="image/*"
required>

</div>



<div class="form-group">

<label>Width (px)</label>

<input 
type="number"
name="width"
placeholder="Enter Width"
required>

</div>




<div class="form-group">

<label>Height (px)</label>

<input 
type="number"
name="height"
placeholder="Enter Height"
required>

</div>




<button type="submit" name="resize">

Resize Image

</button>



</form>





<!-- Preview Before Upload -->

<div class="preview">

<img id="previewImage" style="display:none;">

</div>






<?php if($message!=""){ ?>


<div class="success">

<?php echo $message; ?>

</div>



<div class="preview">

<img src="<?php echo $output; ?>">

</div>



<div class="download">

<a href="<?php echo $output; ?>" download>

⬇ Download Resized Image

</a>

</div>



<?php } ?>





</div>





<script>


// Image preview before upload

let imageInput = document.getElementById("imageInput");

let previewImage = document.getElementById("previewImage");



imageInput.addEventListener("change",function(){


let file=this.files[0];


if(file){


let reader=new FileReader();



reader.onload=function(e){


previewImage.src=e.target.result;

previewImage.style.display="block";


}



reader.readAsDataURL(file);


}


});



</script>




</body>

</html>