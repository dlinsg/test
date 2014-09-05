use Data::Dumper;

sub my_append{
	my $new_item = shift;
	my @a_list = @_ || ();
	push(@a_list, $new_item);
	return @a_list;
}

@x = my_append('One');
print Dumper(@x);

@y = my_append('Two');
print Dumper(@y);


