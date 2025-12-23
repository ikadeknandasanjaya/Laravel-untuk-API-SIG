#!/bin/bash

echo "🚀 Setting up GeoFeature System..."
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Copying from .env.example..."
    cp .env.example .env
    echo "✅ Please update .env with your Supabase credentials!"
    echo ""
fi

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction

echo ""
echo "📦 Installing Node dependencies..."
npm install

# Generate app key if needed
echo ""
echo "🔑 Generating application key..."
php artisan key:generate

# Run migrations
echo ""
echo "🗄️  Running database migrations..."
read -p "⚠️  This will reset your database. Continue? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan migrate:fresh
    
    echo ""
    echo "🌱 Seeding database with sample data..."
    php artisan db:seed --class=GeoFeatureSeeder
    
    echo ""
    echo "✅ Database setup complete!"
fi

# Build frontend
echo ""
echo "🎨 Building frontend assets..."
npm run build

echo ""
echo "✅ Setup complete!"
echo ""
echo "📝 Next steps:"
echo "1. Update .env with your Supabase credentials"
echo "2. Start the development server: php artisan serve"
echo "3. Start Vite dev server: npm run dev"
echo "4. Visit http://localhost:8000"
echo ""
echo "📚 See GEOFEATURE_SETUP.md for API documentation"
