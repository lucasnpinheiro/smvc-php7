<?php
namespace Helpers;

class Datagrid
{

    /*
     * Protected variables
     */
    protected $getHtml, $noDataMessage;

    public $source;
    // source for this array
    protected $colWidths;
    // array of widths for each cols
    protected $colMapping;
    // array of columns whose content needs to be hyperlinked.
    protected $stripSlashes;
    // strip slashes in cell data displayed
    protected $style, $headerStyle, $trClass, $tdClass;

    protected $width, $border, $padding, $spacing, $borderColor;

    protected $hiddenColumns = array();

    protected $class, $_alias, $colWid, $_alternativeColors, $columnWidths;

    public function __construct()
    {
        $this->tableCssClass = 'table table-hover';
        $this->noDataMessage = \Helpers\HTMLGenerators::alertBox('No records to display');
        
        $this->source = new \stdClass();
        $this->source->indexRows = true;
        $this->source->rows = array();
        $this->source->fields = array();
        $this->source->rowCount = 0;
        $this->source->colCount = 0;
        
        $this->preserveColumnNames = false; // if false, _ chars in col names will be replaced with spaces.
    }

    private function _validate()
    {
        
        /* if rowcount and colcount is not set, count it */
        $this->source->rowCount = count($this->source->rows);
        $this->source->colCount = count($this->source->fields);
        
        $extras = '';
        
        // if no source
        
        if ((! is_object($this->source) || $this->source->rowCount == 0) and ! $this->getHtml) {
            if (! $this->noDataMessage)
                $this->html = 'No data/source is assigned to this datagrid';
            else
                $this->html = '<p>' . $this->noDataMessage . '</p>';
            return false;
        }
        
        // default style
        if (empty($this->style) && $this->tableCssClass == '') {
            $this->style = "font-family:Arial; font-size:12px";
        }
        // other style information
        $this->border = $this->border ? $this->border : 0;
        $this->padding = $this->padding ? $this->padding : 4;
        $this->spacing = $this->spacing ? $this->spacing : 0;
        
        $extras .= ' border="' . $this->border . '"';
        $extras .= ' cellpadding="' . $this->padding . '"';
        $extras .= ' cellspacing="' . $this->spacing . '"';
        if ($this->borderColor)
            $extras .= ' bordercolor="' . $this->borderColor . '"';
        
        if (! $this->width)
            $this->width = '100%';
        $extras .= ' width="' . $this->width . '"';
        
        // $this->staticStyles=$this->border. $this->padding. $this->spacing. $this->width. $this->borderColor;
        $extras .= ' style="' . $this->style . '"';
        
        if ($this->tableCssClass != '')
            $extras .= ' class="' . $this->tableCssClass . '"';
        
        $this->_staticStyles = $extras;
        
        if (! $this->headerStyle && $this->tableCssClass == '')
            $this->headerStyle = "background-color:#DDDDDD;";
        
        return true;
    }

    public function render()
    {
        /*
         * Variable initialization
         */
        $colWid = '';
        $acBgColor = '';
        $nl = "\n";
        $this->html = '';
        
        /*
         * validate styles and other info before rendering
         */
        if (! $this->_validate()) {
            if ($this->getHtml) {
                return $this->html;
            } else
                echo $this->html;
            return false;
        }
        
        /*
         * start the table
         */
        $this->html .= '<table ' . $this->_staticStyles . ' name="' . $this->_tableName . '" id="' . $this->_tableName . '">' . $nl;
        
        /*
         * display headers and col headings
         */
        $this->html .= '<thead>' . $nl . '	<tr style="' . $this->headerStyle . '">' . $nl;
        for ($i = 0; $i < $this->source->colCount; $i ++) {
            /*
             * if column has to be hidden
             */
            if (in_array($this->source->fields[$i], $this->hiddenColumns))
                continue;
            
            if ($this->columnWidths[$i])
                $colWid = ' width="' . $this->columnWidths[$i] . '"';
                
                /*
             * if there is any columnalias
             */
            
            if (! empty($this->_alias[$this->source->fields[$i]]))
                $colName = $this->_alias[$this->source->fields[$i]];
            else
                $colName = $this->source->fields[$i];
                
                /* format column names if required */
            if ($this->preserveColumnNames == false) {
                $colName = ucwords(str_replace(array(
                    '_'
                ), ' ', $this->source->fields[$i]));
            } else
                $colName = ucfirst($colName);
            $this->html .= '		<th' . $colWid . '><strong>' . $colName . '</strong></th>' . $nl;
            $colName = '';
        }
        $this->html .= '	</tr></thead>' . $nl;
        
        /*
         * display data
         * flip fields for colmapping keys, if required.
         */
        //
        if (count($this->colMapping) > 0)
            $fieldFlip = array_flip($this->source->fields);
        $this->html .= '<tbody>' . $nl;
        for ($i = 0; $i < $this->source->rowCount; $i ++) {
            
            // if to skip first row
            if ($i == 0 && $this->skipFirstRow)
                continue;
            
            if ($this->_alternativeColors)
                $acBgColor = ' bgcolor="' . $this->_alternativeColors[($i % 2)] . '"';
            $this->html .= '	<tr' . $this->trClass . $acBgColor . '>' . $nl;
            for ($j = 0; $j < $this->source->colCount; $j ++) {
                // if this column is hidden
                if (in_array($this->source->fields[$j], $this->hiddenColumns))
                    continue;
                    
                    // if this col needs to be linked
                if (! empty($this->colMapping[$this->source->fields[$j]]) and ! $this->getHtml) {
                    // display linked col info
                    $urlMap = $this->colMapping[$this->source->fields[$j]];
                    $mapParts = explode('<COL:', $urlMap);
                    $urlString = '';
                    for ($k = 1; $k < count($mapParts); $k ++) {
                        $col = explode('>', $mapParts[$k]); // explode the column name
                        if (! empty($this->source->indexRows))
                            $colValue = $this->source->rows[$i][$col[0]];
                        else
                            $colValue = $this->source->rows[$i][$fieldFlip[$col[0]]];
                        array_shift($col);
                        $urlString .= $colValue . join('', $col);
                    }
                    $urlString = $mapParts[0] . $urlString;
                    
                    $this->html .= '		<td' . $this->tdClass . '><a href="' . $urlString . '"';
                    if ($this->colMappingProperties[$this->source->fields[$j]]['clickConfirmationMessage'] != '')
                        $this->html .= ' onclick="return confirm(\'' . $this->colMappingProperties[$this->source->fields[$j]]['clickConfirmationMessage'] . '\')" ';
                    $this->html .= ' >';
                    
                    if ($this->source->indexRows)
                        $cellContent = $this->source->rows[$i][$this->source->fields[$j]];
                    else
                        $cellContent = $this->source->rows[$i][$j];
                        // strip slashes if asked for
                    $cellContent = $this->stripSlashes ? stripslashes($cellContent) : $cellContent;
                    
                    $this->html .= $cellContent;
                    $this->html .= '</a></td>' . $nl;
                    $urlString = '';
                    $mapParts = '';
                } else {
                    // display normal table, without including colMapping
                    $this->html .= '		<td>';
                    if ($this->source->indexRows)
                        $this->html .= $this->source->rows[$i][$this->source->fields[$j]];
                    else
                        $this->html .= $this->source->rows[$i][$j];
                    $this->html .= '</td>' . $nl;
                }
            }
            $this->html .= '	</tr>' . $nl;
        }
        $this->html .= '</tbody>' . $nl;
        // end table
        $this->html .= '<tfoot></tfoot></table>';
        if ($this->getHtml) {
            $this->getHtml = false;
            return $this->html;
        } else
            echo $this->html;
    }

    public function getHtml()
    {
        $this->getHtml = true;
        return $this->render();
    }

    public function setColumnAlias($columnName, $displayName)
    {
        $this->_alias[$columnName] = $displayName;
    }

    public function hideColumn($columnName)
    {
        $this->hiddenColumns[] = $columnName;
    }

    public function hideColumns()
    {
        if (is_array(func_get_arg(0))) {
            foreach (func_get_arg(0) as $colName) {
                $this->hideColumn($colName);
            }
        } else {
            for ($i = 0; $i < func_num_args(); $i ++) {
                $this->hideColumn(func_get_arg($i));
            }
        }
    }

    public function addColumn($columnName, $value = '')
    {
        /* add the column to the list of fields */
        $this->source->fields[] = $columnName;
        
        /* if the $value!='', then add column values to the row */
        if ($this->source->rowCount > 0 && $value != '') {
            if ($this->source->indexRows) {
                for ($i = 0; $i < $this->source->rowCount; $i ++) {
                    $this->source->rows[$i][$columnName] = $value;
                }
            } else {
                for ($i = 0; $i < $this->source->rowCount; $i ++) {
                    $this->source->rows[$i][] = $value;
                }
            }
        }
    }

    public function setColumnLinking($columnName, $linkpattern, $extraHTML = '', $onClickConfirmationMessage = '')
    {
        if ($onClickConfirmationMessage != '')
            $this->colMappingProperties[$columnName]['clickConfirmationMessage'] = $onClickConfirmationMessage;
        $this->colMapping[$columnName] = $linkpattern;
    }

    public function addRow(array $rowArray)
    {
        /* if array is passed, add it as it is */
        if (is_array($rowArray)) {
            $this->source->rows[] = $rowArray;
            $this->source->fields = array_keys($rowArray);
        } else {
            /* else if arguments are passed, add all args as a single row */
            $this->source->rows[] = func_get_args();
        }
        $this->source->rowCount ++;
    }

    public function setRowAlternativeColors($color1, $color2)
    {
        $this->_alternativeColors = array(
            $color1,
            $color2
        );
    }

    public function setTableCssClass($cssClassName)
    {
        $this->tableCssClass = $cssClassName;
    }

    public function setNoDataMessage($message)
    {
        $this->noDataMessage = $message;
    }

    public function getNoDataMessage()
    {
        return $this->noDataMessage;
    }

    public function setDataSource($source)
    {
        $this->source = $source;
    }

    public function setSkipFirstRow($bool = false)
    {
        $this->skipFirstRow = $bool;
    }

    public function setColumnWidths($widthsArray)
    {
        $this->columnWidths = $widthsArray;
    }

    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }

    public function preserveColumnNames($boolValue = false)
    {
        $this->preserveColumnNames = $boolValue;
    }

    public function clear()
    {
        unset($this->source);
        $this->source = new stdClass();
    }

    /**
     * reset() - alias of clear();
     */
    public function reset()
    {
        $this->clear();
    }
    
    //
    /**
     * Returns paging links like 1 - 2 - [3] - 4 - 5
     *
     * @param string $pageToLink
     *            Denote with a caret e.g./Mypage/page/^/id/23
     * @param string $displayText
     *            Denote page number with caret e.g. Page - ^
     * @param int $totalRecCount            
     * @param int $perPageCount            
     * @param int $highlightPage
     *            page number to be highlighted
     * @param bool $useDefaultLayout
     *            if true plain text, if false, colored boxes
     */
    public static function generatePagingLinks($pageToLink, $displayText = ' ^ ', $totalRecCount, $perPageCount = 20, $highlightPage = 1, $useDefaultLayout = true)
    {
        $links = array();
        
        /*
         * Calculate how many pages are required
         */
        $pageCount = ceil($totalRecCount / $perPageCount);
        
        if ($useDefaultLayout) {
            /* replace carets with page numbers and create links. */
            
            for ($i = 0; $i < $pageCount; $i ++) {
                if ($i == $highlightPage)
                    $links[] = ' [ ' . str_replace('^', $i + 1, $displayText) . ' ] ';
                else
                    $links[] = '<a href="' . str_replace('^', $i + 1, $pageToLink) . '">' . str_replace('^', $i + 1, $displayText) . '</a>';
            }
            
            $pagingText = '<p>';
            $pagingText .= 'Pages: ' . join(' - ', $links);
            $pagingText .= '</p>';
        } else {
            $highlightStyle = 'padding:5px 8px 5px 8px;border:1px solid #d9d9d9;background:orange;color:black;font-weight:bold;';
            $normalStyle = 'padding:3px 5px 3px 5px;border:1px solid #d9d9d9;background:#d9d9d9;color:black;';
            
            for ($i = 0; $i < $pageCount; $i ++) {
                if ($i == $highlightPage)
                    $links[] = '<span style="' . $highlightStyle . '"> ' . str_replace('^', $i + 1, $displayText) . ' </span>';
                else
                    $links[] = '<span style="' . $normalStyle . '"><a href="' . str_replace('^', $i + 1, $pageToLink) . '">' . str_replace('^', $i + 1, $displayText) . '</a></span>';
            }
            
            $pagingText = '<p><span style="' . $normalStyle . '">&nbsp;</span>';
            $pagingText .= '' . join('', $links);
            $pagingText .= '<span style="' . $normalStyle . '">&nbsp;</span></p>';
        }
        
        /*
         * normal
         * padding:3px 5px 3px 5px;border:1px solid #d9d9d9;background:#d9d9d9;color:black;
         *
         * highlighted
         * padding:5px 8px 5px 8px;border:1px solid #d9d9d9;background:orange;color:black;font-weight:bold;
         */
        
        // die($pageCount.'**'. $totalRecCount);
        return $pagingText;
    }
}
