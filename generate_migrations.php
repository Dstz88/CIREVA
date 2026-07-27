<?php

$migrationsDir = __DIR__ . '/database/migrations';
$timestamp = time();

$migrations = [
    [
        'name' => 'create_organizer_documents_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizer_documents', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_profile_id')->constrained()->cascadeOnDelete();
            \$table->string('document_type', 100);
            \$table->string('file_path', 255);
            \$table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            \$table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            \$table->dateTime('verified_at')->nullable();
            \$table->text('rejection_reason')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizer_documents');
    }
};
PHP
    ],
    [
        'name' => 'create_cooperation_agreements_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperation_agreements', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_profile_id')->constrained()->cascadeOnDelete();
            \$table->string('agreement_number', 100)->unique();
            \$table->string('version', 20);
            \$table->string('file_path', 255);
            \$table->dateTime('signed_at')->nullable();
            \$table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            \$table->dateTime('approved_at')->nullable();
            \$table->text('rejected_reason')->nullable();
            \$table->dateTime('expired_at')->nullable();
            \$table->enum('status', ['draft', 'generated', 'pending_signature', 'signed', 'under_review', 'revision_required', 'approved', 'rejected', 'expired'])->default('draft')->index();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperation_agreements');
    }
};
PHP
    ],
    [
        'name' => 'create_event_categories_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_categories', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name', 100);
            \$table->string('slug', 120)->unique();
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_categories');
    }
};
PHP
    ],
    [
        'name' => 'create_event_locations_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_locations', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name', 150);
            \$table->text('address');
            \$table->decimal('latitude', 10, 7);
            \$table->decimal('longitude', 10, 7);
            \$table->integer('capacity')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_locations');
    }
};
PHP
    ],
    [
        'name' => 'create_events_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('organizer_profile_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('category_id')->constrained('event_categories')->restrictOnDelete();
            \$table->foreignId('location_id')->constrained('event_locations')->restrictOnDelete();
            \$table->string('title', 200);
            \$table->string('slug', 220)->unique();
            \$table->longText('description');
            \$table->string('banner', 255);
            \$table->enum('status', ['draft', 'submitted', 'under_review', 'revision_required', 'approved', 'published', 'ongoing', 'finished', 'archived'])->default('draft')->index();
            \$table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            \$table->dateTime('approved_at')->nullable();
            \$table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            \$table->dateTime('published_at')->nullable();
            \$table->timestamps();
            \$table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
PHP
    ],
    [
        'name' => 'create_event_schedules_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('event_id')->constrained()->cascadeOnDelete();
            \$table->dateTime('start_datetime');
            \$table->dateTime('end_datetime');
            \$table->string('timezone', 50);
            \$table->enum('status', ['draft', 'scheduled', 'published', 'ongoing', 'finished', 'cancelled'])->default('draft')->index();
            \$table->timestamps();

            \$table->index(['event_id', 'start_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
PHP
    ],
    [
        'name' => 'create_tickets_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('event_id')->constrained()->cascadeOnDelete();
            \$table->string('name', 150);
            \$table->text('description')->nullable();
            \$table->decimal('price', 12, 2);
            \$table->integer('quota');
            \$table->integer('sold')->default(0);
            \$table->enum('status', ['active', 'inactive', 'sold_out'])->default('inactive')->index();
            \$table->dateTime('sale_start')->nullable();
            \$table->dateTime('sale_end')->nullable();
            \$table->timestamps();
            \$table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
PHP
    ],
    [
        'name' => 'create_bookings_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained()->restrictOnDelete();
            \$table->string('booking_code', 50)->unique();
            \$table->decimal('total_amount', 12, 2);
            \$table->enum('status', ['pending', 'paid', 'cancelled', 'completed', 'expired'])->default('pending')->index();
            \$table->dateTime('expired_at')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
PHP
    ],
    [
        'name' => 'create_booking_items_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('ticket_id')->constrained()->restrictOnDelete();
            \$table->integer('quantity');
            \$table->decimal('price', 12, 2);
            \$table->decimal('subtotal', 12, 2);
            \$table->timestamps();
            
            \$table->index(['booking_id', 'ticket_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
PHP
    ],
    [
        'name' => 'create_transactions_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('booking_id')->constrained()->restrictOnDelete();
            \$table->string('transaction_number', 100)->unique();
            \$table->string('payment_method', 50);
            \$table->decimal('amount', 12, 2);
            \$table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending')->index();
            \$table->dateTime('paid_at')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
PHP
    ],
    [
        'name' => 'create_payment_proofs_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_proofs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            \$table->string('file_path', 255);
            \$table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            \$table->dateTime('verified_at')->nullable();
            \$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_proofs');
    }
};
PHP
    ],
    [
        'name' => 'create_notifications_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->string('title', 200);
            \$table->text('message');
            \$table->boolean('is_read')->default(false)->index();
            \$table->dateTime('read_at')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
PHP
    ],
    [
        'name' => 'create_reports_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint \$table) {
            \$table->id();
            \$table->string('report_type', 100);
            \$table->foreignId('generated_by')->constrained('users')->restrictOnDelete();
            \$table->dateTime('generated_at');
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
PHP
    ],
    [
        'name' => 'create_activity_logs_table',
        'content' => <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->string('module', 100);
            \$table->string('action', 100);
            \$table->text('description');
            \$table->string('ip_address', 45);
            \$table->text('user_agent')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
PHP
    ]
];

foreach ($migrations as $index => $migration) {
    $fileTimestamp = date('Y_m_d_His', $timestamp + $index);
    $filename = $migrationsDir . '/' . $fileTimestamp . '_' . $migration['name'] . '.php';
    file_put_contents($filename, $migration['content']);
    echo "Created: " . basename($filename) . "\n";
}
