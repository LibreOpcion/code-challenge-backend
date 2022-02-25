<?php
class Peripheral
{
    /**
     * You get the most expensive peripheral [mouse, keyboard] within the budget
     *
     * @param float $budget
     * @return array|false
     */
    public static function getOnBudget(float $budget)
    {
        # json to use
        $json = (object)[
            'mouses'    => file_get_contents('data/json/Mouses.json'),
            'keyboards' => file_get_contents('data/json/Teclados.json')
        ];

        $mouses    = json_decode($json->mouses);
        $keyboards = json_decode($json->keyboards);

        # get a collection below budget, in descending order
        $peripheral_mouse    = self::sortPeripheralUnderBudget($mouses, $budget);
        $peripheral_keyboard = self::sortPeripheralUnderBudget($keyboards, $budget);

        $result = [];

        foreach ($peripheral_mouse as $value_mouse) {
            foreach ($peripheral_keyboard as $value_keyboard) {
                if (($value_mouse->precio + $value_keyboard->precio) == $budget) {
                    $result[] = ['mouse' => $value_mouse, 'keyboard' => $value_keyboard];
                }
            }
        }

        return !empty($result) ? reset($result) : false;
    }

    /**
     * @param $product
     * @param float $budget
     * @return array
     */
    private static function sortPeripheralUnderBudget($product, float $budget): array
    {
        $result = [];
        foreach ($product as $value) {
            if ($value->precio < $budget) {
                $result[] = (object)[
                    'precio'     => $value->precio,
                    'id_interno' => $value->id_interno,
                    'item'       => $value->item
                ];
            }
        }
        # sort descending
        rsort($result);
        # return object
        return $result;
    }

}

print_r(Peripheral::getOnBudget(31570));