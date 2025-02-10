@extends('layouts.student-dashboard')

@section('title', 'My Grades')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Student Info Header -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <span class="text-xl font-semibold">
                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-gray-600">Student ID: {{ $student->id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- General Note -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $allGrades = collect($grades)->flatten();
                    $totalGrades = $allGrades->count();
                    
                    // Calculate overall weighted average
                    $totalWeightedSum = 0;
                    $totalModuleFactors = 0;
                    
                    foreach ($grades as $moduleName => $moduleGrades) {
                        // Calculate module grade
                        $moduleWeightedSum = 0;
                        $moduleFactorSum = 0;
                        
                        foreach ($moduleGrades as $grade) {
                            $controlWeightedGrade = ($grade->grade_value / $grade->control->max_grade) 
                                * $grade->control->factor;
                            $moduleWeightedSum += $controlWeightedGrade;
                            $moduleFactorSum += $grade->control->factor;
                        }
                        
                        if ($moduleFactorSum > 0) {
                            $moduleGrade = ($moduleWeightedSum / $moduleFactorSum) 
                                * $moduleGrades->first()->control->module->factor;
                            $totalWeightedSum += $moduleGrade;
                            $totalModuleFactors += $moduleGrades->first()->control->module->factor;
                        }
                    }
                    
                    $overallGrade = $totalModuleFactors > 0 ? 
                        ($totalWeightedSum / $totalModuleFactors) * 20 : 0;
                    
                    // Calculate passed controls
                    $passedGrades = $allGrades->filter(function($grade) {
                        return ($grade->grade_value / $grade->control->max_grade) >= 0.5;
                    })->count();
                @endphp
                
                <div class="bg-indigo-50 rounded-lg p-4">
                    <p class="text-sm text-indigo-600 font-medium">Overall Grade</p>
                    <p class="text-2xl font-bold text-indigo-700">{{ number_format($overallGrade, 2) }}/20</p>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-green-600 font-medium">Passed Controls</p>
                    <p class="text-2xl font-bold text-green-700">{{ $passedGrades }}/{{ $totalGrades }}</p>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-blue-600 font-medium">Success Rate</p>
                    <p class="text-2xl font-bold text-blue-700">
                        {{ $totalGrades > 0 ? number_format(($passedGrades / $totalGrades) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades List -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($grades as $moduleName => $moduleGrades)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ $moduleName }}
                    </h2>
                </div>

                <!-- Module Statistics -->
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            // Calculate module weighted grade
                            $moduleWeightedSum = 0;
                            $moduleFactorSum = 0;
                            
                            foreach ($moduleGrades as $grade) {
                                $controlWeightedGrade = ($grade->grade_value / $grade->control->max_grade) 
                                    * $grade->control->factor;
                                $moduleWeightedSum += $controlWeightedGrade;
                                $moduleFactorSum += $grade->control->factor;
                            }
                            
                            // Final module grade (on scale of 20)
                            $moduleFactor = $moduleGrades->first()->control->module->factor;
                            $moduleGrade = $moduleFactorSum > 0 ? 
                                ($moduleWeightedSum / $moduleFactorSum) * $moduleFactor * 20 : 0;
                            
                            // Calculate success metrics
                            $passedModuleControls = $moduleGrades->filter(function($grade) {
                                return ($grade->grade_value / $grade->control->max_grade) >= 0.5;
                            })->count();
                            
                            $totalModuleControls = $moduleGrades->count();
                            $successRate = ($totalModuleControls > 0) ? 
                                ($passedModuleControls / $totalModuleControls) * 100 : 0;
                        @endphp
                        
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Module Grade</p>
                            <p class="text-lg font-semibold text-indigo-600">
                                {{ number_format($moduleGrade, 2) }}/20
                            </p>
                            <p class="text-xs text-gray-400">Factor: {{ $moduleFactor }}</p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Success Rate</p>
                            <p class="text-lg font-semibold {{ $successRate >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($successRate, 1) }}%
                            </p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 shadow-sm">
                            <p class="text-xs text-gray-500 mb-1">Passed/Total</p>
                            <p class="text-lg font-semibold text-gray-700">
                                {{ $passedModuleControls }}/{{ $totalModuleControls }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Grades Table -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Control Factor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weighted Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($moduleGrades as $grade)
                                    @php
                                        // Calculate control's weighted grade (on scale of 20)
                                        $normalizedGrade = $grade->grade_value / $grade->control->max_grade;
                                        $weightedGrade = $normalizedGrade * $grade->control->factor * 20;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $grade->control->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $normalizedGrade >= 0.5 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $grade->grade_value }}/{{ $grade->control->max_grade }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $grade->control->max_grade }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $grade->control->factor }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                            {{ number_format($weightedGrade, 2) }}/20
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $grade->created_at->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl p-8 text-center shadow-lg">
                <div class="inline-block p-4 bg-indigo-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No Grades Available</h3>
                <p class="text-gray-500">You don't have any grades recorded yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection 