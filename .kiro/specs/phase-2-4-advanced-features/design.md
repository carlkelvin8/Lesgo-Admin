# Design Document: Phase 2-4 Advanced Features

## Architecture Overview

### System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     Filament Admin Panel                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │  Resources   │  │   Widgets    │  │    Pages     │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                      Application Layer                           │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │  Controllers │  │   Services   │  │    Jobs      │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │   Events     │  │  Listeners   │  │   Actions    │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                       Domain Layer                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │    Models    │  │  Policies    │  │   Observers  │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                    Infrastructure Layer                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │  PostgreSQL  │  │    Redis     │  │   Storage    │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────────┐
│                    External Services                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │    Stripe    │  │    Twilio    │  │   SendGrid   │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │  Google Maps │  │   AWS S3     │  │    Pusher    │         │
│  └──────────────┘  └──────────────┘  └──────────────┘         │
└─────────────────────────────────────────────────────────────────┘
```

## Phase 2: Quick Wins - Database Schema

### 1. GPS Tracking System

#### vehicle_locations table
```sql
- id: bigint primary key
- vehicle_id: bigint foreign key
- order_id: bigint foreign key nullable
- latitude: decimal(10,8)
- longitude: decimal(11,8)
- accuracy: decimal(5,2) meters
- speed: decimal(5,2) km/h nullable
- heading: decimal(5,2) degrees nullable
- recorded_at: timestamp
- created_at: timestamp
```

#### route_optimizations table
```sql
- id: bigint primary key
- driver_id: bigint foreign key
- optimization_date: date
- total_distance: decimal(8,2) km
- estimated_duration: integer minutes
- actual_duration: integer minutes nullable
- fuel_saved: decimal(6,2) liters nullable
- status: enum(pending, optimized, in_progress, completed)
- created_at: timestamp
- updated_at: timestamp
```

#### route_waypoints table
```sql
- id: bigint primary key
- route_optimization_id: bigint foreign key
- order_id: bigint foreign key
- sequence: integer
- estimated_arrival: timestamp
- actual_arrival: timestamp nullable
- latitude: decimal(10,8)
- longitude: decimal(11,8)
- status: enum(pending, reached, skipped)
- created_at: timestamp
```

### 2. Advanced Reporting

#### report_templates table
```sql
- id: bigint primary key
- name: string
- description: text nullable
- type: enum(revenue, orders, partners, drivers, vehicles, custom)
- configuration: json
- created_by: bigint foreign key
- is_public: boolean default false
- created_at: timestamp
- updated_at: timestamp
```

#### scheduled_reports table
```sql
- id: bigint primary key
- report_template_id: bigint foreign key
- name: string
- frequency: enum(daily, weekly, monthly)
- schedule_time: time
- recipients: json (email addresses)
- format: enum(pdf, excel, csv)
- last_run_at: timestamp nullable
- next_run_at: timestamp
- is_active: boolean default true
- created_at: timestamp
- updated_at: timestamp
```

#### report_executions table
```sql
- id: bigint primary key
- scheduled_report_id: bigint foreign key nullable
- report_template_id: bigint foreign key
- executed_by: bigint foreign key nullable
- status: enum(pending, processing, completed, failed)
- file_path: string nullable
- file_size: bigint nullable
- record_count: integer nullable
- execution_time: integer milliseconds nullable
- error_message: text nullable
- created_at: timestamp
- completed_at: timestamp nullable
```

### 3. Audit Logging

#### audit_logs table
```sql
- id: bigint primary key
- user_id: bigint foreign key nullable
- event: string (e.g., 'user.created', 'order.updated')
- auditable_type: string
- auditable_id: bigint
- old_values: json nullable
- new_values: json nullable
- ip_address: string nullable
- user_agent: text nullable
- tags: json nullable
- created_at: timestamp
```

#### security_events table
```sql
- id: bigint primary key
- user_id: bigint foreign key nullable
- event_type: enum(login_success, login_failed, logout, password_changed, 2fa_enabled, 2fa_disabled, suspicious_activity)
- ip_address: string
- user_agent: text
- metadata: json nullable
- severity: enum(info, warning, critical)
- created_at: timestamp
```

### 4. Granular Permissions

#### roles table (extends existing)
```sql
- id: bigint primary key
- name: string unique
- slug: string unique
- description: text nullable
- is_system: boolean default false
- parent_id: bigint foreign key nullable (for role inheritance)
- created_at: timestamp
- updated_at: timestamp
```

#### permissions table
```sql
- id: bigint primary key
- name: string unique
- slug: string unique
- resource: string (e.g., 'orders', 'users')
- action: enum(view, create, update, delete, export, manage)
- description: text nullable
- created_at: timestamp
- updated_at: timestamp
```

#### role_permission table
```sql
- role_id: bigint foreign key
- permission_id: bigint foreign key
- primary key (role_id, permission_id)
```

#### user_permissions table (for user-specific overrides)
```sql
- user_id: bigint foreign key
- permission_id: bigint foreign key
- granted: boolean default true
- primary key (user_id, permission_id)
```

### 5. Payment Gateway Integration

#### payment_gateways table
```sql
- id: bigint primary key
- name: string
- slug: string unique
- is_active: boolean default true
- configuration: json encrypted
- transaction_fee_percentage: decimal(5,2)
- transaction_fee_fixed: decimal(8,2)
- supported_currencies: json
- created_at: timestamp
- updated_at: timestamp
```

#### payment_transactions table
```sql
- id: bigint primary key
- payment_id: bigint foreign key
- gateway_id: bigint foreign key
- gateway_transaction_id: string nullable
- amount: decimal(10,2)
- currency: string(3) default 'PHP'
- status: enum(pending, processing, completed, failed, refunded)
- gateway_response: json nullable
- fee_amount: decimal(10,2) nullable
- net_amount: decimal(10,2) nullable
- processed_at: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
```

### 6. Notification System

#### notification_templates table
```sql
- id: bigint primary key
- name: string
- slug: string unique
- channel: enum(email, sms, push, database)
- subject: string nullable
- body: text
- variables: json
- is_active: boolean default true
- created_at: timestamp
- updated_at: timestamp
```

#### notification_logs table
```sql
- id: bigint primary key
- user_id: bigint foreign key
- template_id: bigint foreign key nullable
- channel: enum(email, sms, push, database)
- recipient: string
- subject: string nullable
- body: text
- status: enum(pending, sent, delivered, failed, bounced)
- sent_at: timestamp nullable
- delivered_at: timestamp nullable
- opened_at: timestamp nullable
- clicked_at: timestamp nullable
- error_message: text nullable
- metadata: json nullable
- created_at: timestamp
```

#### notification_preferences table
```sql
- id: bigint primary key
- user_id: bigint foreign key
- notification_type: string
- email_enabled: boolean default true
- sms_enabled: boolean default true
- push_enabled: boolean default true
- database_enabled: boolean default true
- created_at: timestamp
- updated_at: timestamp
- unique(user_id, notification_type)
```

### 7. Workflow Engine

#### workflows table
```sql
- id: bigint primary key
- name: string
- description: text nullable
- trigger_type: enum(event, schedule, manual)
- trigger_config: json
- is_active: boolean default true
- created_by: bigint foreign key
- created_at: timestamp
- updated_at: timestamp
```

#### workflow_actions table
```sql
- id: bigint primary key
- workflow_id: bigint foreign key
- sequence: integer
- action_type: enum(send_notification, update_record, create_task, call_webhook, delay)
- action_config: json
- conditions: json nullable
- created_at: timestamp
- updated_at: timestamp
```

#### workflow_executions table
```sql
- id: bigint primary key
- workflow_id: bigint foreign key
- trigger_data: json
- status: enum(pending, running, completed, failed)
- started_at: timestamp
- completed_at: timestamp nullable
- error_message: text nullable
- created_at: timestamp
```

#### workflow_execution_logs table
```sql
- id: bigint primary key
- workflow_execution_id: bigint foreign key
- workflow_action_id: bigint foreign key
- status: enum(pending, running, completed, failed, skipped)
- input_data: json nullable
- output_data: json nullable
- error_message: text nullable
- executed_at: timestamp
```

### 8. ML Forecasting

#### demand_forecasts table
```sql
- id: bigint primary key
- forecast_date: date
- service_id: bigint foreign key nullable
- region: string nullable
- predicted_orders: integer
- confidence_lower: integer
- confidence_upper: integer
- actual_orders: integer nullable
- accuracy_percentage: decimal(5,2) nullable
- model_version: string
- created_at: timestamp
```

### 9. Fraud Detection

#### fraud_scores table
```sql
- id: bigint primary key
- order_id: bigint foreign key
- risk_score: integer (0-100)
- risk_level: enum(low, medium, high, critical)
- risk_factors: json
- model_version: string
- reviewed_by: bigint foreign key nullable
- reviewed_at: timestamp nullable
- review_decision: enum(approved, rejected, flagged) nullable
- review_notes: text nullable
- created_at: timestamp
- updated_at: timestamp
```

#### fraud_rules table
```sql
- id: bigint primary key
- name: string
- description: text
- rule_type: enum(velocity, pattern, anomaly, blacklist)
- conditions: json
- risk_weight: integer
- is_active: boolean default true
- created_at: timestamp
- updated_at: timestamp
```

## Phase 3: Growth Features - Database Schema

### 10. Multi-Tenant Architecture

#### tenants table
```sql
- id: bigint primary key
- name: string
- slug: string unique
- domain: string unique nullable
- database_name: string unique
- is_active: boolean default true
- subscription_tier: enum(free, basic, professional, enterprise)
- subscription_expires_at: timestamp nullable
- settings: json
- created_at: timestamp
- updated_at: timestamp
```

#### tenant_users table
```sql
- tenant_id: bigint foreign key
- user_id: bigint foreign key
- role: string
- primary key (tenant_id, user_id)
```

### 11. Document Management

#### documents table
```sql
- id: bigint primary key
- documentable_type: string
- documentable_id: bigint
- category: enum(invoice, receipt, contract, kyc, insurance, license, other)
- title: string
- file_name: string
- file_path: string
- file_size: bigint
- mime_type: string
- version: integer default 1
- uploaded_by: bigint foreign key
- is_verified: boolean default false
- verified_by: bigint foreign key nullable
- verified_at: timestamp nullable
- expires_at: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp nullable
```

#### document_versions table
```sql
- id: bigint primary key
- document_id: bigint foreign key
- version: integer
- file_path: string
- file_size: bigint
- uploaded_by: bigint foreign key
- change_notes: text nullable
- created_at: timestamp
```

### 12. Dispute Resolution

#### disputes table
```sql
- id: bigint primary key
- ticket_number: string unique
- order_id: bigint foreign key
- customer_id: bigint foreign key
- category: enum(delivery_issue, payment_problem, service_quality, damaged_goods, other)
- priority: enum(low, medium, high, urgent)
- status: enum(open, assigned, in_progress, resolved, closed, escalated)
- subject: string
- description: text
- assigned_to: bigint foreign key nullable
- resolution: text nullable
- customer_satisfaction: integer nullable (1-5)
- created_at: timestamp
- updated_at: timestamp
- resolved_at: timestamp nullable
- closed_at: timestamp nullable
```

#### dispute_messages table
```sql
- id: bigint primary key
- dispute_id: bigint foreign key
- user_id: bigint foreign key
- message: text
- is_internal: boolean default false
- attachments: json nullable
- created_at: timestamp
```

### 13. Commission Management

#### commission_structures table
```sql
- id: bigint primary key
- name: string
- type: enum(percentage, fixed, tiered)
- configuration: json
- applies_to: enum(partner, driver, both)
- is_active: boolean default true
- effective_from: date
- effective_until: date nullable
- created_at: timestamp
- updated_at: timestamp
```

#### commissions table
```sql
- id: bigint primary key
- order_id: bigint foreign key
- partner_id: bigint foreign key nullable
- driver_id: bigint foreign key nullable
- commission_structure_id: bigint foreign key
- base_amount: decimal(10,2)
- commission_rate: decimal(5,2)
- commission_amount: decimal(10,2)
- status: enum(pending, on_hold, approved, paid)
- hold_until: timestamp nullable
- approved_by: bigint foreign key nullable
- approved_at: timestamp nullable
- paid_at: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
```

#### payouts table
```sql
- id: bigint primary key
- payout_number: string unique
- payee_type: string (Partner or DriverProfile)
- payee_id: bigint
- total_amount: decimal(10,2)
- commission_count: integer
- status: enum(pending, processing, completed, failed)
- payment_method: enum(bank_transfer, cash, wallet)
- payment_reference: string nullable
- processed_by: bigint foreign key nullable
- processed_at: timestamp nullable
- notes: text nullable
- created_at: timestamp
- updated_at: timestamp
```

#### payout_commissions table
```sql
- payout_id: bigint foreign key
- commission_id: bigint foreign key
- primary key (payout_id, commission_id)
```

### 14. Dynamic Pricing

#### pricing_rules table
```sql
- id: bigint primary key
- name: string
- type: enum(surge, discount, distance, time_based)
- priority: integer
- conditions: json
- adjustment_type: enum(percentage, fixed, multiplier)
- adjustment_value: decimal(8,2)
- is_active: boolean default true
- valid_from: timestamp nullable
- valid_until: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
```

#### discount_codes table
```sql
- id: bigint primary key
- code: string unique
- description: text nullable
- type: enum(percentage, fixed_amount, free_delivery)
- value: decimal(8,2)
- min_order_value: decimal(10,2) nullable
- max_discount: decimal(10,2) nullable
- usage_limit: integer nullable
- usage_count: integer default 0
- user_limit: integer default 1
- valid_from: timestamp
- valid_until: timestamp
- is_active: boolean default true
- created_at: timestamp
- updated_at: timestamp
```

#### discount_code_usages table
```sql
- id: bigint primary key
- discount_code_id: bigint foreign key
- order_id: bigint foreign key
- user_id: bigint foreign key
- discount_amount: decimal(10,2)
- created_at: timestamp
```

### 15. Loyalty Program

#### loyalty_tiers table
```sql
- id: bigint primary key
- name: string
- points_required: integer
- benefits: json
- color: string
- icon: string nullable
- sequence: integer
- created_at: timestamp
- updated_at: timestamp
```

#### loyalty_accounts table
```sql
- id: bigint primary key
- user_id: bigint foreign key unique
- tier_id: bigint foreign key
- points_balance: integer default 0
- lifetime_points: integer default 0
- tier_achieved_at: timestamp nullable
- created_at: timestamp
- updated_at: timestamp
```

#### loyalty_transactions table
```sql
- id: bigint primary key
- loyalty_account_id: bigint foreign key
- order_id: bigint foreign key nullable
- type: enum(earned, redeemed, expired, adjusted)
- points: integer
- description: string
- expires_at: timestamp nullable
- created_at: timestamp
```

## Component Architecture

### Service Layer

```
app/Services/
├── GPS/
│   ├── LocationTracker.php
│   └── RouteOptimizer.php
├── Reporting/
│   ├── ReportGenerator.php
│   └── ReportScheduler.php
├── Audit/
│   └── AuditLogger.php
├── Permission/
│   └── PermissionManager.php
├── Payment/
│   ├── PaymentGatewayManager.php
│   ├── Gateways/
│   │   ├── StripeGateway.php
│   │   ├── PayPalGateway.php
│   │   └── RazorpayGateway.php
├── Notification/
│   ├── NotificationService.php
│   └── Channels/
│       ├── SmsChannel.php
│       └── PushChannel.php
├── Workflow/
│   └── WorkflowEngine.php
├── ML/
│   ├── DemandForecaster.php
│   └── FraudDetector.php
├── Commission/
│   └── CommissionCalculator.php
├── Pricing/
│   └── PricingEngine.php
└── Loyalty/
    └── LoyaltyManager.php
```

### Job Architecture

```
app/Jobs/
├── GPS/
│   ├── ProcessLocationUpdate.php
│   └── OptimizeRoutes.php
├── Reporting/
│   ├── GenerateReport.php
│   └── SendScheduledReport.php
├── Notification/
│   ├── SendEmailNotification.php
│   ├── SendSmsNotification.php
│   └── SendPushNotification.php
├── ML/
│   ├── TrainForecastModel.php
│   └── CalculateFraudScores.php
├── Commission/
│   ├── CalculateCommissions.php
│   └── ProcessPayout.php
└── Workflow/
    └── ExecuteWorkflow.php
```

### Event Architecture

```
app/Events/
├── Order/
│   ├── OrderCreated.php
│   ├── OrderStatusChanged.php
│   └── OrderCompleted.php
├── Payment/
│   ├── PaymentProcessed.php
│   └── PaymentFailed.php
├── Location/
│   └── VehicleLocationUpdated.php
├── Fraud/
│   └── HighRiskOrderDetected.php
└── Workflow/
    └── WorkflowTriggered.php
```

## API Endpoints

### GPS Tracking
```
POST   /api/v1/locations                    - Record location
GET    /api/v1/vehicles/{id}/location       - Get current location
GET    /api/v1/orders/{id}/tracking         - Get order tracking
POST   /api/v1/routes/optimize               - Optimize routes
```

### Reporting
```
GET    /api/v1/reports/templates             - List templates
POST   /api/v1/reports/generate              - Generate report
GET    /api/v1/reports/{id}/download         - Download report
POST   /api/v1/reports/schedule              - Schedule report
```

### Audit
```
GET    /api/v1/audit-logs                    - List audit logs
GET    /api/v1/audit-logs/{id}               - Get audit log
GET    /api/v1/resources/{type}/{id}/audit   - Get resource audit trail
```

### Permissions
```
GET    /api/v1/roles                         - List roles
POST   /api/v1/roles                         - Create role
GET    /api/v1/permissions                   - List permissions
POST   /api/v1/roles/{id}/permissions        - Assign permissions
```

### Payments
```
POST   /api/v1/payments/process              - Process payment
POST   /api/v1/payments/{id}/refund          - Refund payment
POST   /api/v1/webhooks/stripe               - Stripe webhook
POST   /api/v1/webhooks/paypal               - PayPal webhook
```

### Notifications
```
POST   /api/v1/notifications/send            - Send notification
GET    /api/v1/notifications/preferences     - Get preferences
PUT    /api/v1/notifications/preferences     - Update preferences
```

### Workflows
```
GET    /api/v1/workflows                     - List workflows
POST   /api/v1/workflows                     - Create workflow
POST   /api/v1/workflows/{id}/execute        - Execute workflow
GET    /api/v1/workflows/{id}/executions     - Get execution history
```

## Package Dependencies

```json
{
  "require": {
    "stripe/stripe-php": "^13.0",
    "paypal/rest-api-sdk-php": "^1.14",
    "razorpay/razorpay": "^2.9",
    "twilio/sdk": "^7.0",
    "sendgrid/sendgrid": "^8.0",
    "pusher/pusher-php-server": "^7.2",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-activitylog": "^4.7",
    "spatie/laravel-query-builder": "^5.6",
    "maatwebsite/excel": "^3.1",
    "barryvdh/laravel-dompdf": "^2.0",
    "predis/predis": "^2.2",
    "aws/aws-sdk-php": "^3.290",
    "league/flysystem-aws-s3-v3": "^3.0",
    "guzzlehttp/guzzle": "^7.8"
  }
}
```

## Configuration Files

```
config/
├── gps.php              - GPS tracking configuration
├── reporting.php        - Report generation settings
├── audit.php            - Audit logging configuration
├── payment-gateways.php - Payment gateway credentials
├── notifications.php    - Notification channels
├── workflows.php        - Workflow engine settings
├── ml.php               - ML model configuration
└── loyalty.php          - Loyalty program settings
```

## Caching Strategy

### Cache Keys
```
- user_permissions:{user_id}           - TTL: 1 hour
- role_permissions:{role_id}           - TTL: 1 hour
- vehicle_location:{vehicle_id}        - TTL: 1 minute
- fraud_score:{order_id}               - TTL: 24 hours
- demand_forecast:{date}:{service_id}  - TTL: 1 day
- pricing_rules:active                 - TTL: 5 minutes
- loyalty_tier:{user_id}               - TTL: 1 hour
```

### Cache Invalidation
- Permission changes: Clear user/role permission caches
- Location updates: Update vehicle location cache
- Pricing rule changes: Clear pricing rules cache
- Loyalty transactions: Clear loyalty tier cache

## Queue Configuration

### Queue Names
```
- default      - General background jobs
- gps          - Location processing
- reports      - Report generation
- notifications - Email, SMS, push notifications
- ml           - ML model training and predictions
- workflows    - Workflow executions
- high         - High priority tasks
```

### Queue Workers
```
php artisan queue:work --queue=high,gps,notifications,default
php artisan queue:work --queue=reports,ml,workflows
```

## Security Considerations

1. **API Authentication**: Laravel Sanctum for API tokens
2. **Rate Limiting**: Throttle API requests per user/IP
3. **Encryption**: Encrypt sensitive data (payment credentials, documents)
4. **HTTPS**: Enforce HTTPS for all API endpoints
5. **CORS**: Configure allowed origins
6. **Input Validation**: Validate all user inputs
7. **SQL Injection**: Use Eloquent ORM and prepared statements
8. **XSS Protection**: Sanitize output
9. **CSRF Protection**: Laravel CSRF tokens
10. **Audit Logging**: Log all sensitive operations

## Performance Optimization

1. **Database Indexing**: Index foreign keys and frequently queried columns
2. **Query Optimization**: Use eager loading, select specific columns
3. **Caching**: Cache frequently accessed data
4. **Queue Jobs**: Process heavy tasks asynchronously
5. **CDN**: Serve static assets from CDN
6. **Database Connection Pooling**: Use persistent connections
7. **Response Compression**: Enable gzip compression
8. **Lazy Loading**: Load data on demand
9. **Pagination**: Paginate large datasets
10. **Database Read Replicas**: Separate read/write operations

## Implementation Order

### Phase 2 - Week 1-2
1. Audit Logging (Foundation for all features)
2. Granular Permissions (Security foundation)
3. Advanced Reporting (Business value)

### Phase 2 - Week 3-4
4. Payment Gateway Integration
5. Notification System
6. Workflow Engine

### Phase 2 - Week 5-6
7. GPS Tracking
8. Route Optimization
9. Fraud Detection
10. Demand Forecasting

### Phase 3 - Week 7-8
11. Document Management
12. Dispute Resolution
13. Commission Management

### Phase 3 - Week 9-10
14. Dynamic Pricing
15. Loyalty Program
16. Multi-Tenant Architecture

## Testing Strategy

1. **Unit Tests**: Test individual services and methods
2. **Feature Tests**: Test API endpoints and workflows
3. **Integration Tests**: Test external service integrations
4. **Performance Tests**: Load testing for critical paths
5. **Security Tests**: Penetration testing and vulnerability scanning

## Deployment Checklist

- [ ] Run database migrations
- [ ] Seed initial data (roles, permissions, pricing rules)
- [ ] Configure external service credentials
- [ ] Set up queue workers
- [ ] Configure cron jobs for scheduled tasks
- [ ] Set up Redis for caching
- [ ] Configure file storage (S3)
- [ ] Set up monitoring and logging
- [ ] Configure backup strategy
- [ ] Run security audit
- [ ] Performance testing
- [ ] Documentation update
