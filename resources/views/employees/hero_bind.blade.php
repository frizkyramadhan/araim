@php
	$linkedHeroId = old('hero_employee_id', isset($employee) ? $employee->hero_employee_id : '');
	$heroLink = $heroLink ?? null;
	if ($linkedHeroId && is_array($heroLink) && (string) ($heroLink['id'] ?? '') === (string) $linkedHeroId) {
		$linkedPerson = $heroLink;
	} elseif ($linkedHeroId) {
		$linkedPerson = ['id' => $linkedHeroId, 'fullname' => 'Linked person', 'nik' => '', 'project' => ''];
	} else {
		$linkedPerson = null;
	}
@endphp
<div class="form-group">
	<div class="card card-outline card-info mb-0" id="hero-link-card">
		<div class="card-header">
			<h3 class="card-title"><i class="fas fa-link mr-1"></i> Link to ARKA HERO</h3>
			<div class="card-tools">
				<span class="badge {{ $linkedPerson ? 'badge-info' : 'badge-secondary' }}" id="hero-link-badge">
					{{ $linkedPerson ? 'Link' : 'Not linked' }}
				</span>
			</div>
		</div>
		<div class="card-body">
			<input type="hidden" name="hero_employee_id" id="hero_employee_id" value="{{ $linkedHeroId }}">
			<div id="hero-link-summary" class="hero-link-summary {{ $linkedPerson ? 'is-linked' : '' }}">
				<div id="hero-link-copy">
					@if ($linkedPerson)
						<div class="hero-link-name">{{ $linkedPerson['fullname'] !== '' ? $linkedPerson['fullname'] : 'Linked person' }}</div>
						<div class="hero-link-meta">
							@php
								$metaParts = array_values(array_filter([
									$linkedPerson['nik'] !== '' ? 'NIK '.$linkedPerson['nik'] : null,
									$linkedPerson['project'] !== '' ? $linkedPerson['project'] : null,
								]));
							@endphp
							{{ $metaParts !== [] ? implode(' · ', $metaParts) : 'Name, NIK, and project from ARKA HERO' }}
						</div>
					@else
						<div class="hero-link-name">Not linked</div>
						<div class="hero-link-meta">Search the person in ARKA HERO by name or current NIK. The link follows that person.</div>
					@endif
				</div>
				<button class="btn btn-outline-danger btn-sm {{ $linkedPerson ? '' : 'd-none' }}" type="button" id="hero-clear-btn">
					Remove link
				</button>
			</div>
			<label class="sr-only" for="hero-search-q">Search ARKA HERO</label>
			<div class="input-group">
				<input type="text" class="form-control" id="hero-search-q" placeholder="Name or current NIK" autocomplete="off">
				<div class="input-group-append">
					<button class="btn btn-info" type="button" id="hero-search-btn"><i class="fas fa-search mr-1"></i> Search</button>
				</div>
			</div>
			<div class="mt-3" id="hero-search-results"></div>
			@error('hero_employee_id')
				<div class="text-danger mt-2">{{ $message }}</div>
			@enderror
		</div>
	</div>
</div>
<style>
	#hero-link-card .hero-link-summary {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		border: 1px dashed #ced4da;
		border-radius: .25rem;
		padding: .75rem 1rem;
		margin-bottom: 1rem;
		background: #f8f9fa;
	}
	#hero-link-card .hero-link-summary.is-linked {
		border-style: solid;
		border-color: #17a2b8;
		background: #f3fbfd;
	}
	#hero-link-card .hero-link-name {
		font-weight: 600;
	}
	#hero-link-card .hero-link-meta {
		color: #6c757d;
		font-size: .875rem;
		margin-top: .15rem;
	}
	#hero-link-card .hero-link-person {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		border: 1px solid #dee2e6;
		border-radius: .25rem;
		padding: .65rem .85rem;
		margin-bottom: .5rem;
		background: #fff;
	}
	#hero-link-card .hero-link-person.is-linked {
		border-color: #17a2b8;
		background: #f3fbfd;
	}
	#hero-link-card .hero-link-note {
		color: #6c757d;
		font-size: .875rem;
	}
</style>
<script>
	(function () {
		var hidden = document.getElementById('hero_employee_id');
		var badge = document.getElementById('hero-link-badge');
		var summary = document.getElementById('hero-link-summary');
		var copy = document.getElementById('hero-link-copy');
		var clearBtn = document.getElementById('hero-clear-btn');
		var query = document.getElementById('hero-search-q');
		var results = document.getElementById('hero-search-results');

		function personMeta(person) {
			var parts = [];
			if (person && person.nik) {
				parts.push('NIK ' + person.nik);
			}
			if (person && person.project) {
				parts.push(person.project);
			}
			return parts.join(' · ');
		}

		function setLink(person) {
			if (!person || !person.id) {
				hidden.value = '';
				badge.className = 'badge badge-secondary';
				badge.textContent = 'Not linked';
				summary.classList.remove('is-linked');
				copy.innerHTML = '<div class="hero-link-name">Not linked</div><div class="hero-link-meta">Search the person in ARKA HERO by name or current NIK. The link follows that person.</div>';
				clearBtn.classList.add('d-none');
				return;
			}
			hidden.value = person.id;
			badge.className = 'badge badge-info';
			badge.textContent = 'Link';
			summary.classList.add('is-linked');
			var name = person.fullname ? person.fullname : 'Linked person';
			var meta = personMeta(person);
			copy.innerHTML = '<div class="hero-link-name"></div><div class="hero-link-meta"></div>';
			copy.querySelector('.hero-link-name').textContent = name;
			copy.querySelector('.hero-link-meta').textContent = meta || 'Name, NIK, and project from ARKA HERO';
			clearBtn.classList.remove('d-none');
		}

		function markSelected() {
			var people = results.querySelectorAll('.hero-link-person');
			for (var i = 0; i < people.length; i++) {
				var selected = people[i].getAttribute('data-id') === hidden.value;
				people[i].classList.toggle('is-linked', selected);
				var button = people[i].querySelector('button');
				if (button) {
					button.textContent = selected ? 'Linked' : 'Link';
					button.className = selected ? 'btn btn-info btn-sm' : 'btn btn-outline-info btn-sm';
				}
			}
		}

		clearBtn.addEventListener('click', function () {
			setLink(null);
			markSelected();
		});

		query.addEventListener('keydown', function (event) {
			if (event.key === 'Enter') {
				event.preventDefault();
				document.getElementById('hero-search-btn').click();
			}
		});

		document.getElementById('hero-search-btn').addEventListener('click', function () {
			var term = query.value.trim();
			results.innerHTML = '';
			if (!term) {
				return;
			}
			results.innerHTML = '<div class="hero-link-note">Searching...</div>';
			fetch(@json(route('employees.heroSearch')) + '?q=' + encodeURIComponent(term), {
				headers: { 'Accept': 'application/json' },
				credentials: 'same-origin'
			}).then(function (response) {
				return response.json().then(function (body) {
					return { ok: response.ok, body: body };
				});
			}).then(function (result) {
				results.innerHTML = '';
				if (!result.ok) {
					var error = document.createElement('div');
					error.className = 'text-danger';
					error.textContent = (result.body && result.body.message) ? result.body.message : 'Hero search failed.';
					results.appendChild(error);
					return;
				}
				var people = (result.body && result.body.data) ? result.body.data : [];
				if (!people.length) {
					results.innerHTML = '<div class="hero-link-note">No Hero employee found.</div>';
					return;
				}
				people.forEach(function (person) {
					var row = document.createElement('div');
					row.className = 'hero-link-person';
					row.setAttribute('data-id', person.id);
					var text = document.createElement('div');
					var name = document.createElement('div');
					name.className = 'hero-link-name';
					name.textContent = person.fullname || 'Linked person';
					var meta = document.createElement('div');
					meta.className = 'hero-link-meta';
					meta.textContent = personMeta(person);
					text.appendChild(name);
					text.appendChild(meta);
					var button = document.createElement('button');
					button.type = 'button';
					button.addEventListener('click', function () {
						setLink(person);
						markSelected();
					});
					row.appendChild(text);
					row.appendChild(button);
					results.appendChild(row);
				});
				markSelected();
			}).catch(function () {
				results.innerHTML = '<div class="text-danger">Hero search failed.</div>';
			});
		});
	})();
</script>
