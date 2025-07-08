<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KMS - Simple Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Knowledge Management System</h1>
        
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Divisions ({{ $divisions->count() }})</h2>
            
            @foreach($divisions as $division)
                <div class="border-b border-gray-200 py-4 last:border-b-0">
                    <h3 class="font-medium text-lg">{{ $division->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $division->activities->count() }} activities</p>
                    <p class="text-gray-500 mt-2">{{ $division->description }}</p>
                    
                    <div class="mt-3">
                        <a href="{{ route('kms.division', $division->slug) }}" 
                           class="inline-block bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                            View Activities
                        </a>
                        <a href="{{ route('kms.divisions.edit', $division->slug) }}" 
                           class="inline-block bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 ml-2">
                            Edit
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(isset($recentDocuments) && $recentDocuments->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Documents ({{ $recentDocuments->count() }})</h2>
                
                @foreach($recentDocuments as $document)
                    <div class="border-b border-gray-200 py-4 last:border-b-0">
                        <h3 class="font-medium">{{ $document->title }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $document->activity->division->name }} - {{ $document->activity->name }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $document->document_date->format('d F Y') }}</p>
                        <a href="{{ $document->onedrive_link }}" target="_blank" 
                           class="inline-block bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 mt-2">
                            View Document
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-6">
            <a href="{{ route('kms.divisions.create') }}" 
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Create New Division
            </a>
        </div>
    </div>
</body>
</html>
