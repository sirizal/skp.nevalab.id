# Add custom_action (Bulk Receive) on getHeaderAction ListReceives

1. Find PurchaseOrder with full_received='N' and iterate it
2. For Every PurchaseOrder create Receive record :
    - code format : GRN-SKP-yymm-001 : yymm following Purchase purchase_date and running_number(001) reset when yymm change
    - receive_date follow Purchase purchase_date
    - vendor_id follow Purchase vendor_id
    - document_date follow Purchase purchase_date
    - document_no format : $purchase->vendor->sj_profix-yymm-001 ymm following Purchase purchase_date and running_number(001) reset when yymm change
    - purchase_id and user_id are follow
3. After create Receive record , add ReceiveItem based on related PurchaseItem , follow logic on CreateReceive.php afterCreate function
