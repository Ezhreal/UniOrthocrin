use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('library_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->constrained('library')->cascadeOnDelete();
            $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
            $table->boolean('can_view')->default(true);
            $table->boolean('can_download')->default(true);
            $table->timestamps();
            
            $table->unique(['library_id', 'user_type_id']);
            $table->index(['user_type_id', 'can_view']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_permissions');
    }
}; 