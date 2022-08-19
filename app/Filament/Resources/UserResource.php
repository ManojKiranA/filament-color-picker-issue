<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Support\HtmlString;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),

                     Forms\Components\Fieldset::make('filed_set_without_searchable')
                            ->label('Filed Set Without Searchable')
                            ->schema([

                           Forms\Components\Select::make('parent_select_without_searchable')
                            ->label('Parent Select')
                            ->validationAttribute('Parent Select')
                            ->placeholder('Select Parent Select')
                            ->options(collect(array_combine(range(1,10),range(1,10))))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->columnSpan([
                                'sm' => 6,
                            ]),

                            Forms\Components\Select::make('child_select_without_searchable')
                            ->label('Child Select')
                            ->reactive()
                            ->validationAttribute('Child Select')
                            ->options(function(Closure $get){
                                $tblNumber = $get('parent_select_without_searchable');
                                return collect(range(1,10))
                                ->map(function($value) use ($tblNumber){
                                return [
                                    'key' => $key = $value * $tblNumber,
                                    'value' => Str::of($value)
                                    ->append(' ')
                                    ->append('*')
                                    ->append(' ')
                                    ->append($tblNumber)
                                    ->append(' ')
                                    ->append('=')
                                    ->append(' ')
                                    ->append($key)
                                    ->toString(),
                                ];
                                })
                                ->pluck('value','key');
                            })
                            ->required()
                            ->placeholder('Select Child Select')
                            ->disabled(function(Closure $get){
                                return ! filled($get('parent_select_without_searchable'));
                            })
                            ->helperText(new HtmlString('This will be enabled after selecting <code>parent_select_without_searchable</code>'))
                            ->columnSpan([
                                'sm' => 6,
                            ]),
                            


                            ])
                            ->columns(12),

                            Forms\Components\Fieldset::make('filed_set_wit_searchable')
                            ->label('Filed Set With Searchable')
                            ->schema([

                           Forms\Components\Select::make('parent_select_with_searchable')
                            ->label('Parent Select')
                            ->validationAttribute('Parent Select')
                            ->placeholder('Select Parent Select')
                            ->options(collect(array_combine(range(1,10),range(1,10))))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->columnSpan([
                                'sm' => 6,
                            ]),

                            Forms\Components\Select::make('child_select_with_searchable')
                            ->label('Child Select')
                            ->reactive()
                            ->validationAttribute('Child Select')
                            ->options(function(Closure $get){
                                $tblNumber = $get('parent_select_with_searchable');
                                return collect(range(1,10))
                                ->map(function($value) use ($tblNumber){
                                return [
                                    'key' => $key = $value * $tblNumber,
                                    'value' => Str::of($value)
                                    ->append(' ')
                                    ->append('*')
                                    ->append(' ')
                                    ->append($tblNumber)
                                    ->append(' ')
                                    ->append('=')
                                    ->append(' ')
                                    ->append($key)
                                    ->toString(),
                                ];
                                })
                                ->pluck('value','key');
                            })
                            ->helperText(new HtmlString('This will not be enabled even after selecting <code>parent_select_with_searchable</code>'))
                            ->required()
                            ->searchable()
                            ->placeholder('Select Child Select')
                            ->disabled(function(Closure $get){
                                return ! filled($get('parent_select_with_searchable'));
                            })
                            ->columnSpan([
                                'sm' => 6,
                            ]),
                            


                            ])
                            ->columns(12),

                Forms\Components\Repeater::make('preferred_colors')
                ->label('Preferred Colors')
                ->createItemButtonLabel('Add Color')
    ->schema([
        Forms\Components\ColorPicker::make('color')->required(),

    ])
    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
