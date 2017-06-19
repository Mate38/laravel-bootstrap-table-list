<?php

namespace Okipa\LaravelBootstrapTableList;

use Closure;
use ErrorException;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class TableListColumn extends Model
{
    protected $fillable = [
        'table_list',
        'column_table',
        'has_custom_table',
        'attribute',
        'is_sortable',
        'title',
        'date_format',
        'button_class',
        'image_path_closure',
        'string_limit',
        'is_activation_toggle',
        'configuration_closure',
        'link_closure',
        'html_element_closure',
    ];
    
    /**
     * TableListColumn constructor.
     *
     * @param TableList $table_list
     * @param string    $attribute
     *
     */
    public function __construct(TableList $table_list, string $attribute)
    {
        $this->attributes = [
            'table_list'   => $table_list,
            'column_table' => $table_list->model->getTable(),
            'attribute'    => $attribute,
        ];
        
        return parent::__construct();
    }
    
    /**
     * Set the column title (required)
     *
     * @param string|null $title
     *
     * @return $this|mixed
     */
    function setTitle(string $title = null)
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * Set the default sorted column (required)
     *
     * @param string $direction (accepts "asc" or "desc")
     *
     * @return $this
     * @throws ErrorException
     */
    function sortByDefault(string $direction)
    {
        // we check if the method has already been called
        if ($this->table_list->sortBy || $this->table_list->sortDir) {
            $errorMessage = 'The sortByDefault() method has already been called. You can sort a column by default only once.';
            throw new ErrorException($errorMessage);
        }
        // we set the sort attribute
        $this->table_list->sortBy = $this->attribute;
        // we set the sort direction
        $acceptedDirections = ['asc', 'desc'];
        $errorMessage = 'Invalid $direction argument for sortByAttribute() method. Has to be "asc" or "desc". "' . $direction . '" given.';
        if (!in_array($direction, $acceptedDirections)) throw new InvalidArgumentException($errorMessage);
        $this->table_list->sortDir = $direction;
        
        return $this;
    }
    
    /**
     * Use the column attribute for the destroy confirmation message generation (required)
     *
     * @return $this
     * @throws ErrorException
     *
     */
    function useForDestroyConfirmation()
    {
        if ($this->table_list->destroyAttribute) {
            $errorMessage = 'The useForDestroyConfirmation() method has already been called. You can define a column attribute for the destroy confirmation only once.';
            throw new ErrorException($errorMessage);
        }
        
        $this->table_list->destroyAttribute = $this->attribute;
        
        return $this;
    }
    
    /**
     * Make the column sortable (optional)
     *
     * @return $this
     */
    function isSortable()
    {
        $this->table_list->sortable_columns->add($this);
        $this->is_sortable = true;
        
        return $this;
    }
    
    /**
     * Make the column searchable (optional)
     *
     * @return $this|mixed
     */
    function isSearchable()
    {
        $this->table_list->searchable_columns->add($this);
        
        return $this;
    }
    
    /**
     * Set a custom table for the column (optional)
     * Calling this method can be useful if the column attribute does not directly belong to the table list model
     *
     * @param string $custom_table
     *
     * @return $this|mixed
     */
    function setCustomTable(string $custom_table)
    {
        $this->column_table = $custom_table;
        
        return $this;
    }
    
    /**
     * Set the format for a date (optional)
     * (Carbon is used for formatting the date)
     *
     * @param string|null $date_format
     *
     * @return $this|string
     */
    public function formatDate(string $date_format)
    {
        $this->date_format = $date_format;
        
        return $this;
    }
    
    /**
     * Set the column button class (optional)
     * The attribute is wrapped into a button
     *
     * @param string $button_class
     *
     * @return $this|mixed
     *
     */
    public function isButton(string $button_class)
    {
        $this->button_class = $button_class;
        
        return $this;
    }
    
    /**
     * Set the image path in the method closure (optional)
     *
     * @param Closure $image_path_closure
     *
     * @return $this
     */
    public function isImage(Closure $image_path_closure)
    {
        $this->image_path_closure = $image_path_closure;
        
        return $this;
    }
    
    /**
     * Set the string value display limitation (optional)
     * Shows "..." when the limit is reached
     *
     * @param int $string_limit
     *
     * @return $this
     */
    public function setStringLimit(int $string_limit)
    {
        $this->string_limit = $string_limit;
        
        return $this;
    }
    
    /**
     * Displays an activation toggle to activate / deactivate the entity (optional)
     *
     * @return $this
     */
    public function isActivationToggle()
    {
        $this->is_activation_toggle = true;
        
        return $this;
    }
    
    /**
     * Set the configuration value in the method closure (optional)
     *
     * @param Closure $configuration_closure
     *
     * @return $this
     */
    public function isConfigurationValue(Closure $configuration_closure)
    {
        $this->configuration_closure = $configuration_closure;
        
        return $this;
    }
    
    /**
     * Set the link in the method closure (optional)
     *
     * @param Closure $link_closure
     *
     * @return $this
     */
    public function isLink(Closure $link_closure)
    {
        $this->link_closure = $link_closure;
        
        return $this;
    }
    
    /**
     * Set the HTML element to render in the method closure (optional)
     *
     * @param Closure $html_element_closure
     *
     * @return $this
     */
    public function isHTMLElement(Closure $html_element_closure)
    {
        $this->html_element_closure = $html_element_closure;
        
        return $this;
    }
}