### **Revised Dashboard Layout**

### **Top Row: Key Metrics Summary**

Display these as summary cards with icons for quick reference:

- **Total Active Products:** Display `total_products_active` with an icon representing products.
- **Total Deleted Products:** Display `total_products_deleted` with an icon representing archive or trash.
- **Total Stocks Value:** Display `total_stocks_value` with a currency icon.
- **Total Categories:** Display `total_categories` with a category icon.

### **Second Row: Product Inventory Insights**

Add smaller, colored cards for a quick overview:

- **Low Stock Products:** Display `low_stock_products`, with a color indicator (e.g., red or orange) for urgency.
- **Out of Stock Products:** Display `out_of_stock_products`, also color-coded.
- **Total Stocks:** Display `total_stocks` as a general inventory count.

### **Middle Section: Category Insights**

Organize this into two distinct panels with either bar charts, pie charts, or lists:

1. **Left Panel - Product Count by Category**

   - Use `total_products_by_category` data to display a list, bar chart, or grouped bar chart.
   - Example of the display:
     - "Consequatur - 7 products"
     - "Et - 10 products"
   - Sorting categories from highest to lowest count can make this more visually informative.

2. **Right Panel - Inventory Value by Category**
   - Use `total_inventory_values_by_category` data here, ideally as a pie chart or list.
   - Example:
     - "Category ID 5 - ₱129,973.50"
     - "Category ID 2 - ₱157,638.49"
   - The visual differentiation helps in seeing which categories hold the most inventory value.

### **Bottom Section: Alerts - Recently Added and Recently Updated Products**

This section would be ideal as a tabbed or side-by-side list with interactive details:

1. **Recently Added Products**:

   - Use `recently_added_products` to list product names, descriptions, prices, and category IDs.
   - A clickable “View” button for each product can lead to detailed product information.

2. **Recently Updated Products**:
   - Similarly, use `recently_updated_products` with similar clickable details for updated products.
   - This provides a quick view of recent changes and additions.
