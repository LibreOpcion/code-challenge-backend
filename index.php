<?php

class Combo 
{
    
    /**
     * Obtains best combos within the budget
     * @param $presupuesto  Budget to expend
     *
     * @return \Illuminate\Http\Response
     */
    public static function getAllCombos($presupuesto){
        if (is_numeric($presupuesto)){
            //Obtain data from files and filter non relevant items
            $strJsonFileContentsT = file_get_contents("./data/json/Teclados.json");       
            $arrayTeclados = json_decode($strJsonFileContentsT,true);         
            $arrayTeclados = self::filter($arrayTeclados,$presupuesto);
                   
            $strJsonFileContentsM = file_get_contents("./data/json/Mouses.json");       
            $arrayMouses = json_decode($strJsonFileContentsM,true);  
            $arrayMouses = self::filter($arrayMouses,$presupuesto);  
            
            
            $result = array();
            foreach ($arrayTeclados as $teclado) {
                foreach ($arrayMouses as $mouse) {
                        if($teclado['precio'] + $mouse['precio'] <= $presupuesto){ 
                            array_push($result,['Teclado' => $teclado,'Mouse' => $mouse]);             
                            break;
                        }
                    }
            }  
            return  !empty($result) ? json_encode($result) : false;
        }else{
            return 'El presupuesto ingresado no es válido';
        }
       
    }


     /**
     * Filters products by price and order DESC the final array by price  
     * @param $array  array to filter. 
     * @param $presupuesto  Budget to expend
     * 
     * @return 'filtered and  descending ordered array by price' 
     */
    private static function filter($array, $presupuesto){
        $result = array();
            foreach ($array as $producto) {
                if ($producto['precio'] < $presupuesto ){
                array_push($result,$producto);
                }
            }
        usort($result,function($first,$second){
            return !($first['precio'] > $second['precio']);
            });    
        return $result;
    } 
 
}

print_r(Combo::getAllCombos(31750));