# Loan App APIs

## System Requirements
- Docker & Docker Compose

## Project Setup

```sh
chmod +x setup-project.sh
```

```sh
./setup-project.sh
```
### Shutdown server
```shell
 docker compose down  
```

## API
- Base URL: http://localhost:8000
- Documents: http://localhost:8000/docs
- Postman collections: Import file "Loan APIs.postman_collection.json" in root directory

## Additional Requirements
### Loan
- Currently, the unit of a loan term is "week". This can be extended to include additional enum values such as "month", "quarter".
- Loan amount must be at least 100.
- When a new loan is created, the system will automatically check the borrower's current loans.
  - If there are no overdue repayments, the loan will be automatically approved. Repayments plan will be calculated and generated as well.
  - If there is at least 1 overdue repayment (read below), the loan will be rejected.
- A customer can have many loans at the same time and choose to pay for a specific loan.
- Only approved loans can be paid (other statuses are "rejected", "paid" and "pending").

### Repayment
- When customer deposit money for a loan, a "Payment" (table "payments") will be created
- The created payment will be used to "pay" for 1 or more repayments of that loan, depending on customer's decision (input "pay_future_repayments")
- The excess money will be returned. 
  - For example, the amount of the first repayment is 100, and customer deposit 150 -> Refund amount = 50.
- Every day, a scheduled task will run to find overdue repayments and mark them as "Overdue". An overdue repayment is defined as a repayment that has passed its due date, but has not been paid yet.

