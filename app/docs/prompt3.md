# add custom_action(Buat Invoice) on EditReceive getHeaderActions

1. Update :
    - invoice_no format : $purchase->vendor->inv_prefix-yymm-001 ymm following Receive receive_date and running_number(001) reset when yymm change
    - invoice_date follow Receive receive_date
2. Show invoice_no and invoice_date on ReceiveForm edit mode
