public function form(Form $form): Form
{
return $form
->schema([
Tabs::make('Tabs')
->tabs([
Tabs\Tab::make('Main Info')
->schema([
Section::make()
->schema([
TextInput::make('main_address')
->label('Address')
->maxLength(255),
TextInput::make('main_email')
->label('Main Email')
->email()
->maxLength(255),
]),
]),

Tabs\Tab::make('Sales Department')
->schema([
Section::make()
->schema([
Repeater::make('sales_phones')
->label('Sales Phones')
->schema([
TextInput::make('number')
->label('Phone')
->numeric()
->maxLength(20),
])
->collapsible()
->cloneable(),
TextInput::make('sales_email')
->label('Sales Email')
->email()
->maxLength(255),
]),
]),

Tabs\Tab::make('Export Department')
->schema([
Section::make()
->schema([
TextInput::make('export_phone')
->label('Export Phone')
->numeric()
->maxLength(20),
TextInput::make('export_contact')
->label('Export Contact')
->maxLength(255),
TextInput::make('export_email')
->label('Export Email')
->email()
->maxLength(255),
]),
]),

Tabs\Tab::make('Additional Emails')
->schema([
Section::make()
->schema([
Repeater::make('additional_emails')
->label('Additional Emails')
->schema([
TextInput::make('key')
->label('Email Key')
->maxLength(50),
TextInput::make('value')
->label('Email Value')
->email()
->maxLength(255),
])
->collapsible()
->cloneable(),
]),
]),

Tabs\Tab::make('Map Settings')
->schema([
Section::make()
->schema([
TextInput::make('map_image_alt')
->label('Map Image Alt')
->maxLength(255),
TextInput::make('map_latitude')
->label('Map Latitude')
->numeric(),
TextInput::make('map_longitude')
->label('Map Longitude')
->numeric(),
]),
]),
])
->persistTabInQueryString(),
])
->statePath('data');
}
