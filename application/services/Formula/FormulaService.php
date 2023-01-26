<?php

namespace Application\Services\Formula;

use Application\Services\Formula\Node\Formula;
use Application\Services\Formula\Node\Formula_node;

class FormulaService
{
    /** @var NodeFactory */
    private $nodeFactory;
    
    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }
    
    public function build($input, $types): ?Formula_node
    {
        if ($input === null || strlen($input) == 0)
            return null;
        
        $left_bracket_count = substr_count($input, "(");
        $right_bracket_count = substr_count($input, ")");
        
        if ($left_bracket_count < 1 && $left_bracket_count !== $right_bracket_count)
            return null;
        
        $regular_exp = '/(MAX )?(MIN )?\(([^()]*)\)/';
        
        preg_match('/[)]\s*[(]/', $input, $find);
        if (count($find) > 0)
            return null;
        
        $expression = str_replace(["<p>", "</p>", "<span>", "</span>"], "", $input);
        $expression = str_replace("(", " ( ", $expression);
        $expression = str_replace(")", " ) ", $expression);
        $operatory = ['+', '-', '×', '/', '%', '&lt;=', '&gt;=', '&lt;', '&gt;', '==', '!=', '∧', '∨', '?', ':', '¬'];
        foreach($operatory as $operator) {
            $expression = str_replace($operator, " ".$operator." ", $expression);
        }
        $expression = preg_replace("(&lt; =)", " &lt;= ", $expression);
        $expression = preg_replace("(&gt; =)", " &gt;= ", $expression);
        
        $count = 1;
        $formulas = [];
        
        preg_match_all($regular_exp, $expression, $matches);
        while(true){
            $tokens = $matches[0];
            if (count($tokens) === 0){
                $expression = str_replace(' ', '', $expression);
                
                if (key_exists($expression, $formulas)){
                    return $formulas[$expression];
                }
                else {
                    return null;
                }
            }
            
            foreach($tokens as $token) {
                $formula = $this->build_token($token, $formulas, $types);
                
                // found a mistake within expression
                if ($formula === null){
                    return null;
                }
                
                $token_label = 'T_'. $count;
                $count += 1;
                
                $formulas[$token_label] = $formula;
                $expression = str_replace($token, $token_label, $expression);
            }
            
            $prev = $matches;
            preg_match_all($regular_exp, $expression, $matches);
            
            if ($prev === $matches){
                return null;
            }
        }
        return null;
    }
    
    private function build_token($token, $formulas, $types): ?Formula_node{
        // functions
        if (substr($token, 0, 1) != '('){
            $split = preg_split('/[(,)\s]/', $token, -1, PREG_SPLIT_NO_EMPTY);
            
            if (count($split) !== 3)
                return null;
            $function = $split[0];
            $left = $this->interpret_value($split[1], $formulas, $types);
            $right = $this->interpret_value($split[2], $formulas, $types);
            
            if (in_array(null, [$function, $left, $right]))
                return null;
            
            switch($function){
                case 'MAX':
                    return $this->nodeFactory->getMaxFunction($left, $right);
                case 'MIN':
                    return $this->nodeFactory->getMinFunction($left, $right);
                default:
                    return null;
            }
        }
        else{
            $split = preg_split('/[()\s]/', $token, -1, PREG_SPLIT_NO_EMPTY);
            $count = count($split);
            
            // double brackets e.g. ' ( ( 5 + 6 ) )'
            if ($count == 1)
                return $this->interpret_value($split[0], $formulas, $types);
    
            // unary operator
            if ($count == 2){
                $operator = $split[0];
                $formula = $this->interpret_value($split[1], $formulas, $types);
        
                if ($formula === null)
                    return null;
        
                switch($operator){
                    case '¬':
                        return $this->nodeFactory->getNegation($formula);
                }
            }
            
            // ternary operator
            if ($count === 5) {
                if ($split[1] !== '?' || $split[3] !== ':')
                    return null;
                $condition = $this->interpret_value($split[0], $formulas, $types);
                $left = $this->interpret_value($split[2], $formulas, $types);
                $right = $this->interpret_value($split[4], $formulas, $types);
    
                if (in_array(null, [$condition, $left, $right]))
                    return null;
                
                return $this->nodeFactory->getTernary($left, $right, $condition);
            }
            
            // binary operator
            if ($count == 3) {
                $left = $this->interpret_value($split[0], $formulas, $types);
                $operator = $split[1];
                $right = $this->interpret_value($split[2], $formulas, $types);
    
                if (in_array(null, [$left, $right]))
                    return null;
                
                switch($operator){
                    case '+':
                        return $this->nodeFactory->getAddition($left, $right);
                    case '-':
                        return $this->nodeFactory->getSubtraction($left, $right);
                    case '×':
                        return $this->nodeFactory->getMultiplication($left, $right);
                    case '/':
                        return $this->nodeFactory->getDivision($left, $right);
                    case '%':
                        return $this->nodeFactory->getModulo($left, $right);
                    case '&lt;':
                        return $this->nodeFactory->getSmaller($left, $right);
                    case '&gt;':
                        return $this->nodeFactory->getGreater($left, $right);
                    case '&lt;=':
                        return $this->nodeFactory->getSmallerEqual($left, $right);
                    case '&gt;=':
                        return $this->nodeFactory->getGreaterEqual($left, $right);
                    case '==':
                        return $this->nodeFactory->getEqual($left, $right);
                    case '!=':
                        return $this->nodeFactory->getNotEqual($left, $right);
                    case '∧':
                        return $this->nodeFactory->getConjunction($left, $right);
                    case '∨':
                        return $this->nodeFactory->getDisjunction($left, $right);
                }
            }
        }
        
        return null;
    }
    
    private function interpret_value($token, $formulas, $types): ?Formula_node{
        if (array_key_exists($token, $formulas)){
            return $formulas[$token];
        }
        if (is_numeric($token)){
            return $this->nodeFactory->getConstant(floatval($token));
        }
        $token = str_replace('_', ' ', $token);
        $token = str_replace('~', '', $token);
        if (array_key_exists($token, $types)){
            return $this->nodeFactory->getVariable($token, $types[$token]);
        }
        return null;
    }
    
    public function evaluate_formulas($students_data, $virtual_types): array {
        $results = [];
        
        foreach($students_data as $id=>$student_data){
            $results[$id] = $this->evaluate_formulas_for_student($student_data, $virtual_types);
        }
        
        return $results;
    }
    
    public function evaluate_formulas_for_student($student_data, $virtual_types): array {
        $virtual_types_results = [];
        $to_process = $virtual_types;
        while(true){
            $not_processed = [];
            foreach($to_process as $virtual_type) {
                /** @var Formula $formula*/
                $formula = unserialize($virtual_type->join_formula_object);
                $result = $formula->evaluate($student_data);
                
                if ($result !== null){
                    $student_data[$virtual_type->id] = $result;
                    $virtual_types_results[$virtual_type->id] = $result;
                }
                else {
                    $not_processed[] = $virtual_type;
                }
            }
            
            if (count($not_processed) == 0){
                break;
            }
            
            if (count($not_processed) == count($to_process)) {
                foreach($not_processed as $virtual_type){
                    $virtual_types_results[$virtual_type->id] = null;
                }
                break;
            }
            $to_process = $not_processed;
        }
        
        return $virtual_types_results;
    }
}
