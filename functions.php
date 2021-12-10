<?php 

    function uploadFile($fileToUpload, $allowedTypes, $depth = 0, $uploadDir = "uploads/documents"){

        $relativePath = getRelativePath($depth);

        if(in_array($fileToUpload['type'], $allowedTypes)){
            
            $ext_arr = explode(".", $fileToUpload['name']);
            $extension = end($ext_arr);
            $filename = uniqid().".".$extension;
            
            $newFilePath = $relativePath.$uploadDir."/".$filename;

            if(copy($fileToUpload['tmp_name'], $newFilePath)){
                return $depth > 0 ? str_replace($relativePath, "", "'$newFilePath'") : "'$newFilePath'";
            }
        }else{
            return false;
        }
    }

    function getRelativePath($depth){
        $result = "";
        while($depth > 0){
            $result .= "../";
            $depth--;
        }
        return $result;
    }

    function generateColor($opacity = 1){
        $r = rand(0, 255);
        $g = rand(0, 255);
        $b = rand(0, 255);
        return "rgba($r, $g, $b, $opacity)";
    }
?>