<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->nullable()->after('cost_price');
            $table->boolean('is_customizable')->default(false)->after('is_best_seller');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_whatsapp_subscribed')->default(true)->after('notes');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_source')->default('website')->after('status');
        });

        $this->ensureProductIndexes();
        $this->ensureProductVariantUniqueConstraint();
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_source');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('is_whatsapp_subscribed');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight', 'is_customizable']);
        });

        if ($this->indexExists('product_variants', 'product_variants_product_id_size_id_color_id_unique')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropUnique('product_variants_product_id_size_id_color_id_unique');
            });
        }

        foreach ([
            'products_is_active_index',
            'products_is_featured_index',
            'products_is_best_seller_index',
            'products_is_new_arrival_index',
        ] as $index) {
            if ($this->indexExists('products', $index)) {
                Schema::table('products', function (Blueprint $table) use ($index) {
                    $table->dropIndex($index);
                });
            }
        }
    }

    private function ensureProductIndexes(): void
    {
        // slug, sku: unique() indexes already exist from original migration.
        // category_id, league_id, team_id, product_type_id: indexed by foreign keys.

        $booleanIndexes = [
            'is_active',
            'is_featured',
            'is_best_seller',
            'is_new_arrival',
        ];

        foreach ($booleanIndexes as $column) {
            $indexName = "products_{$column}_index";

            if (! $this->indexExists('products', $indexName)) {
                Schema::table('products', function (Blueprint $table) use ($column) {
                    $table->index($column);
                });
            }
        }

        $foreignKeyColumns = ['category_id', 'league_id', 'team_id', 'product_type_id'];

        foreach ($foreignKeyColumns as $column) {
            if (! $this->columnIsIndexed('products', $column)) {
                Schema::table('products', function (Blueprint $table) use ($column) {
                    $table->index($column);
                });
            }
        }
    }

    private function ensureProductVariantUniqueConstraint(): void
    {
        $indexName = 'product_variants_product_id_size_id_color_id_unique';

        if ($this->indexExists('product_variants', $indexName)) {
            return;
        }

        Schema::table('product_variants', function (Blueprint $table) {
            $table->unique(['product_id', 'size_id', 'color_id']);
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$indexName]);

        return count($indexes) > 0;
    }

    private function columnIsIndexed(string $table, string $column): bool
    {
        $indexes = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Column_name = ?', [$column]);

        return count($indexes) > 0;
    }
};
